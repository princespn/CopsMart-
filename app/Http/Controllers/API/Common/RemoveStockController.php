<?php
namespace App\Http\Controllers\API\Common;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;
use App\ProductImage;
use App\VendorProduct;
use App\Package;
use App\Category;
use App\Size;
use App\Color;
use App\Stock;
use App\StockFetch;
use App\Suggession;
use App\SubCategory;
use App\ProductPackage;
use App\ServiceArea;
use App\RemoveStock;
use App\CoSubCategory;
use App\Vendor;
use App\User;
use DB;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;

class RemoveStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::latest()->with('subCategory')->paginate(10);
    }

    public function all()
    {   
        $id=$_GET['ad'];
        return Product::where('vendor_id',$id)->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

      //  print_r($request->all());
        $this->validate($request, [
            'vendor_id'=>'required',
            'barcode'=>'required',
            'product_id'=>'required',
            'stock_select'=>'required',
            'size'=>'required',
            'color'=>'required',
            'stock'=>'required',
            'qty'=>'required',
        ]);
           $stocks=StockFetch::where('id',$request->stock_select)->first();
           $check1=Stock::where('vendor_id',$request->vendor_id)->where('product_id',$request->product_id)->where('size',$request->size)->where('color',$request->color)->where('price',$stocks->sales_rate)->where('purchase_rate',$stocks->purchase_rate)->orderBy('id','desc')->first();
           if(isset($stocks) && isset($check1))
           {
            $product = new RemoveStock();
            $product->vendor_id = $request->vendor_id;
            $product->product_id = $request->product_id;
            $product->stock_id = $request->stock_select;
            $product->size = $request->size;
            $product->color = $request->color;
            $product->stock = $request->stock;
            $product->qty = $request->qty;
            $product->reason = $request->reason;
            //data
            $product->gst = $check1->gst;
            $product->tgst = $check1->tgst;
            $product->sgst = $check1->sgst;
            $product->cgst = $check1->cgst;
            $product->igst = $check1->igst;
            $product->taxable_rate = $check1->taxable_rate;
            $product->total_price = round($check1->price*$request->qty,2);
            $product->sales_rate = $check1->price;
            $product->purchase_rate = $check1->purchase_rate;
            $product->save();
            $datax['remove_qty']=$request->qty;
            $datax['before_remove_qty']=$check1->quantity;
            $datax['before_remove_qty']=$check1->quantity-$request->qty;
            $datax['quantity']=$check1->quantity-$request->qty;
            $check1->update($datax) ;
           // $check1=StockFetch::where('vendor_id',$request->vendor_id)->where('product_id',$request->product_id)->where('size',$request->size)->where('color',$request->color)->where('sales_rate',$stocks->total_price)->where('purchase_rate',$stocks->purchase_rate)->orderBy('id','desc')->first();
           // print_r($check1);exit;
            $dataxs['quantity']=$stocks->quantity-$request->qty;
            $stocks->update($dataxs) ;
            return [
            'message' => 'created Successfully',
            ];
        }

    }

    function dataTable(Request $request ,$id){
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $orderBy = $request->input('dir', 'asc');
        $searchValue = $request->input('search');
        
        $query = RemoveStock::where('remove_stocks.vendor_id',$id)->join('products','products.id','=','remove_stocks.product_id')
        ->join('sizes','remove_stocks.size','=','sizes.id')->join('colors','remove_stocks.color','=','colors.id')
        ->join('categories','products.category_id','=','categories.id')
        ->select(['remove_stocks.*','products.name as p_v_name','sizes.name as sizename','colors.name as colorname','categories.name as cata_name']);
        if($searchValue!=''){
            $query->where('products.name', "LIKE", "%$searchValue%");
        }
        $data = $query->paginate($length);
        $dt=[];
        if(count($data)>0){
            $dt=[];
            $i=1;
            foreach($data as $key)
            {   
                $key->srno=$i;
                $i++;
            }
        }
        return new DataTableCollectionResource($data);
    }


    // update



    

    
    
}
