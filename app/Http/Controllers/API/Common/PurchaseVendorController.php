<?php

namespace App\Http\Controllers\API\Common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PurchaseVendor;
use JamesDordoy\LaravelVueDatatable\Http\Resources\DataTableCollectionResource;
use DB;

class PurchaseVendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    
    

   


   
   
    // public function index(Request $request)
    // {
    //     // return Category::latest()->paginate(10);
    //     $length = $request->input('length');
    //     $column = $request->input('column'); //Index
    //     $orderBy = $request->input('dir', 'asc');
    //     $searchValue = $request->input('search');

    //     $query = PurchaseVendor::dataTableQuery($column, $orderBy, $searchValue);
    //     $data = $query->paginate($length);

    //     return new DataTableCollectionResource($data);
    // }

    public function index()
    {   
        
       if(isset($_GET['ad']))
       {
           $id=$_GET['ad'];
           if($id!=1)
           {
             $data= PurchaseVendor::where('deleted_at',NULL)->where('vendor_id',$id);
           }
           else{
              $data= PurchaseVendor::where('deleted_at',NULL);
           }
       }
       else
       {
           $data= PurchaseVendor::where('deleted_at',NULL);
       }

       $searchValue = $_GET['table'];
       if($searchValue!=''){
           //echo  $searchValue 
           return $data=$data->where('name', "LIKE", "%$searchValue%")->get();
       }
       else
       {
       return $data->get();
      }
    }
   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {      
        //print_r($request->all());exit;
        $this->validate($request, [
                    'vendor_id'=>'required',
                    'name'=>'required',  
                    'mobile_no'=>'required',
                    'contact_person'=>'required',  
                    'emp_post'=>'required',  
                    'address'=>'required',  
                    'pincode'=>'required',
                    'district'=>'required',
                    'state'=>'required',
                    'gst'=>'required',
                    'bankname'=>'required|string',  
                    'account_name'=>'required',  
                    'account_no'=>'required',
                    'ifsc'=>'required',
                    'ifscverifybank'=>'required',
                    'ifscverify'=>'required',
        ]);
        $request->merge(['name' => ucwords($request->name)]); 
        $request->merge(['contact_person' => ucwords($request->contact_person)]);
        $request->merge(['account_name' => ucwords($request->account_name)]);
        $request->merge(['bankname' => ucwords($request->bankname)]);
        $request->merge(['address' => ucwords($request->address)]);
        $request->merge(['gst' => strtoupper($request->gst)]);
        $request->merge(['emp_post' => ucwords($request->emp_post)]);
        $data = $request->only('vendor_id','name','mobile_no','contact_person','emp_post','address','pincode','district','state','gst','bankname','account_name','account_no','ifsc','ifscverify','ifscverifybank');
        $categoryId = PurchaseVendor::create($data)->id;
        return [
            'category' => $categoryId
        ];
    }


    public function getdistrict($a)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.postalpincode.in/pincode/'.$a,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json',
          ),
       ));
        $response = curl_exec($curl);
        $response= json_decode($response, TRUE);
        if($response[0]['Status']=='Success')
        {
            return ['pro_category'=>$response[0]['PostOffice'][0]];
        }
    }

    public function getaccounntverified(Request $request)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://kyc-api.aadhaarkyc.io/api/v1/bank-verification/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
        "id_number":  "'.$request->account.'",
         "ifsc":  "'.$request->ifsc.'"
        }',
        CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MTY0MTE3MzEsIm5iZiI6MTYxNjQxMTczMSwianRpIjoiMTM3MmEwMjgtNTUyOC00OWMxLWJlNjYtZTdmMWIwOGU5YTU3IiwiZXhwIjoxOTMxNzcxNzMxLCJpZGVudGl0eSI6ImRldi5tZWNoYXRyb250ZWNoZ2VhckBhYWRoYWFyYXBpLmlvIiwiZnJlc2giOmZhbHNlLCJ0eXBlIjoiYWNjZXNzIiwidXNlcl9jbGFpbXMiOnsic2NvcGVzIjpbInJlYWQiXX19.E4-S6d9GrpRrgPT3fFQiV-DKYVBbtDImvlhNf_igMSU'
        ),
        ));
        $data = curl_exec($curl);
        $dataaa= json_decode($data, TRUE);
        if (empty($dataaa) OR ($dataaa['status_code']!=200))
        {
          echo 'NOT';
        }
       else 
       {
        return ['pro_category'=>$dataaa];
       }
    }

public function getifscdata($ifsc)
{
    //$ifsc=$this->input->post('ifsc');
    $curl = curl_init();
      curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://ifsc.razorpay.com/'.$ifsc,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        ),
     ));
      $response = curl_exec($curl);
      //echo $response;
      $response= json_decode($response, TRUE);
     // echo "<pre>";
    // print_r($response);exit;
     //if()
      if($response=='Not Found')
      {
       echo "Not Found";
      }
      else
      {
         return ['pro_category'=>$response];
      }
}

    public function getaccdetails($ifsc , $acnum)
    {   
        //   echo $ifsc;echo $acnum;
        $curl = curl_init();
        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://kyc-api.aadhaarkyc.io/api/v1/bank-verification/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
        "id_number":  "'.$acnum.'",
         "ifsc":  "'.$ifsc.'"
        }',
        CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MTY0MTE3MzEsIm5iZiI6MTYxNjQxMTczMSwianRpIjoiMTM3MmEwMjgtNTUyOC00OWMxLWJlNjYtZTdmMWIwOGU5YTU3IiwiZXhwIjoxOTMxNzcxNzMxLCJpZGVudGl0eSI6ImRldi5tZWNoYXRyb250ZWNoZ2VhckBhYWRoYWFyYXBpLmlvIiwiZnJlc2giOmZhbHNlLCJ0eXBlIjoiYWNjZXNzIiwidXNlcl9jbGFpbXMiOnsic2NvcGVzIjpbInJlYWQiXX19.E4-S6d9GrpRrgPT3fFQiV-DKYVBbtDImvlhNf_igMSU'
        ),
        ));
        $data = curl_exec($curl);
        return $dataaa= json_decode($data, TRUE);
        //     if (empty($dataaa) OR ($dataaa['status_code']!=200))
        //     {
        //       echo 'notdone';
        //     }
        //    else 
        //    {
        //       return $dataaa= json_decode($data, TRUE);
            
        //    }
}
    

    public function loadPurchaseVendorList($a)
    {
        return PurchaseVendor::where('vendor_id',$a)->where('is_block',0)->get();
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return PurchaseVendor::findOrFail($id);
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

        $vendor = PurchaseVendor::findOrFail($id);
        $this->validate($request, [
                    'vendor_id'=>'required',
                    'name'=>'required',
                    'mobile_no'=>'required',
                    'contact_person'=>'required',
                    'emp_post'=>'required',
                    'address'=>'required',
                    'pincode'=>'required',
                    'district'=>'required',
                    'state'=>'required',
                    'gst'=>'required',
                    'bankname'=>'required|string',
                    'account_name'=>'required',
                    'account_no'=>'required',
                    'ifsc'=>'required',
                    'is_active'=>'required',
        ]);
        $data = $request->only('vendor_id','name','mobile_no', 'contact_person', 'emp_post', 'address','pincode', 'district', 'state', 'gst','bankname','account_name','account_no','ifsc','is_active','ifscverifybank','ifscverify');
        //print_r($data);exit;
        $vendor->vendor_id = $request->vendor_id;
        $vendor->name= ucwords($request->name);
        $vendor->mobile_no = $request->mobile_no;
        $vendor->contact_person = ucwords($request->contact_person);
        $vendor->emp_post = ucwords($request->emp_post);
        $vendor->address = ucwords($request->address);
        $vendor->pincode = $request->pincode;
        $vendor->district = $request->district;
        $vendor->state = $request->state;
        $vendor->gst = strtoupper($request->gst);
        $vendor->bankname = ucwords($request->bankname);
        $vendor->account_name = ucwords($request->account_name);
        $vendor->account_no = $request->account_no;
        $vendor->ifsc = $request->ifsc;
        $vendor->ifscverify = $request->ifscverify;
        $vendor->ifscverifybank = $request->ifscverifybank;
        $vendor->is_active = $request->is_active;
        $vendor->is_block = $request->is_block;
        $vendor->update();
      
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
        $category = PurchaseVendor::findOrFail($id);
        $category->delete();
        // delete the category
        return [
            'message' => 'Vendor Deleted !!'
        ];
    }

  
}
