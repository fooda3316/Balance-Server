<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ImageCont extends Controller
{
    public function uploadImage(Request $request) {
        $file = $request->file('image');
        if (!isset($file)){
            $responseData['error'] = true;
            $responseData['message'] = 'The image has not found!!!';
            return response()->json($responseData);
        }
        $filename=$file ->getClientOriginalName() ;
        $filenameExt = $file->getClientOriginalExtension() ;
        $mdName=md5($filename).".".$filenameExt;
        $file ->storeAs('login', $mdName) ;
        if($file==null){
            $responseData['error'] = true;
            $responseData['message'] = 'The image has not uploaded!!!';
            return response()->json($responseData);
        }

        $id=$request->id;
        $user = User::find($id);
        if($user==null){
            $responseData['error'] = true;
            $responseData['message'] = 'عفواً انت غير مُسجل عليك التسجيل اولا !!!';
            return response()->json($responseData);
        }
        $user->image=$mdName;
        if (!$user->save()) {
            $responseData['error'] = true;
            $responseData['message'] = "عفواً حدث خطأ اثناء رفع الصورة!!!";

        }
        $responseData['error'] = false;
		$responseData['image'] = $mdName;
        $responseData['message'] = "تم رفع الصورة بنجاح...";

        return response()->json($responseData);
    }
}
