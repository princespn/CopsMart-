<?php

namespace App\Http\Controllers\API\common;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Splash;
use App\GlobalSetting;
class SplashController extends Controller
{
    public function index(){
        return Splash::first();
    }

    public function update(Request $request, $id){
        $splash = Splash::findOrFail($id);
        $this->validate($request, [
            'image' =>'required'
        ]);
        $oldImage = $splash->image;
        if($request->image){
            $name = time().'.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            \Image::make($request->image)->save(public_path('uploads/images/splash/').$name);
            $request->merge(['name' => $name]);
            $oldImage = public_path('uploads/images/splash/').$oldImage;
            if(file_exists($oldImage)){
                @unlink($oldImage);
            }
        }else{
            $request->merge(['image' => $oldImage]);
        }
        $data = $request->only('name');
        $splash->update($data);
        return [
            'message' => 'Updated Successfully'
        ];
    }

    // public function store(Request $request){
    //     $this->validate($request, $this->rules);
    //     return ['success' => Coupon::create($request->only($this->fields))];
    // }

    // public function destroy($id){
    //     $coupon = Coupon::findOrFail($id);
    //     return ['success' => $coupon->delete()];
    // }

    public function show($id){
        return Splash::findOrFail($id);
    }

    public function splashWithAppVersion(){
        $splash = Splash::first();
        $appVersion = GlobalSetting::pluck('customer_app_min_version')->first();

        return [
            'splash' =>$splash,
            'min_app_version' => $appVersion
        ];
    }
}
