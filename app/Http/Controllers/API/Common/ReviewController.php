<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ProductReview;
use App\DeliveryReview;
use App\User;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;
use DB;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storedeliveryreview(Request $request)
    {     
        $this->validate($request, [
            'star' => 'required|string|max:10',
            // 'review' => 'sometimes|string|max:150',
            'user_id' => 'required',
            'vendor_id'=>'required',
            'order_id'=>'required',
            'delivery_person_id'=>'required',
        ]);  
        $data = $request->only('star','review','vendor_id','user_id','order_id','delivery_person_id');
        $category = DeliveryReview::create($data);
        if(isset($category))
        return [
            'review_id' => $category->id,
            'msg'=>'Review Added Successfully'
        ];
        else
        {
            return [
                'msg'=>'Review Not Added'
            ];
        }
    }
    
    function getdeliveryReview(Request $request ,$id)
    {
        $length = $request->input('length');
        $column = $request->input('column'); //Index
        $orderBy = $request->input('dir', 'asc');
        $searchValue = $request->input('search');
        
        $query = DeliveryReview::where('delivery_review.delivery_person_id',$id)->orderBy('delivery_review.id','desc')->select(['delivery_review.*']);
        if($searchValue!=''){
            $query->where('delivery_review.id', "LIKE", "%$searchValue%");
        }
        $data = $query->paginate($length);
        // echo count($data);exit;
        $dt=[];
        if(count($data)>0){
            $dt=[];
            $i=1;
            foreach($data as $key)
            {   
                $users=User::where('id',$key->user_id)->first();
                if(isset($users))
                {
                    $key->nameuser=$users->name;
                }
                else
                {
                    $key->nameuser='Account Removed';
                }
                $key->srno=$i;
                $key->orderdate=date('d-m-Y H:i A',strtotime($key->created_at));
                $dt[]=$key;
                $i++;
            }
            $dt=$data;
          
            return new DataTableCollectionResource($dt);
        }
        else
        {
            return new DataTableCollectionResource($data);
        }
        
     
       
    }





    public function storeproductreview(Request $request)
    {     
        $this->validate($request, [
            'star' => 'required|string|max:10',
            // 'review' => 'sometimes|string|max:150',
            'user_id' => 'required',
            'vendor_id'=>'required',
            'order_id'=>'required',
            'product_id'=>'required',
        ]);  
        $data = $request->only('star','review','vendor_id','user_id','order_id','product_id');
        $category = ProductReview::create($data);
        if(isset($category))
        return [
            'review_id' => $category->id,
            'msg'=>'Review Added Successfully'
        ];
        else
        {
            return [
                'msg'=>'Review Not Added'
            ];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Attribute::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Attribute::findOrFail($id);
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'type' => 'required|string|max:191',
        ]);
        $cat=ucwords($request->name);
        $request->merge(['name' => $cat]);
        $data = $request->only('name','type','vendor_id','is_active');
        $category->update($data);
        return [
            'message' => 'Updated Successfully'
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Attribute::findOrFail($id);
        $category->delete();
        // delete the category
        return [
            'message' => 'Category Deleted !'
        ];
    }

  
}
