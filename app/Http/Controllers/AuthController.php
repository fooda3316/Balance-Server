<?php

namespace App\Http\Controllers;

use App\Models\Phone;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //private $phone;
    public function register(Request $request) {

        /*$file = $request->file('image');
        if (!isset($file)){
            $responseData['error'] = true;
            $responseData['message'] = 'The image has not uploaded!!!';
        return response()->json($responseData);
        }*/
       $request->validate([
            'name' => 'required|string',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|confirmed',
            'type' => 'required|string'


        ]);
        /*$filename=$file ->getClientOriginalName() ;
        $filenameExt = $file->getClientOriginalExtension() ;
        $mdName=md5($filename).".".$filenameExt;
        $file ->storeAs('login', $mdName) ;*/
        /*$user = User::create([
            'name' => $fields['name'],
            'phone' => $fields['phone'],
            'password' => bcrypt($fields['password']),
            'image' => $mdName,
            'balance' => 0,
        ]);*/
        $user = new User();
        $user->name = $request->name;
        $user->phone = $request->phone;
        $user->password =bcrypt( $request->password);
        $user->image = "";
        $user->balance = 0;
        $result = $user->save();
        if ($result) {
            $number = $request->get('phone');
            $type = $request->get('type');

       //     $user = User::where('phone', '=', "{$number}")->get();

            if ($user==null){
                $responseData['error'] = true;
                $responseData['message'] = 'There are no user!!!';
                return response()->json($responseData);
            }
            $user_id=$user->id;
            $token = $user->createToken('myapptoken')->plainTextToken;
            $phone=new Phone();
            $phone->user_id=$user_id;
            switch ($type){
                case "zain":
                    $phone->zain=$number;
                    $phone->mtn="";
                    $phone->sudani="";
                    break;
                case "mtn":
                    $phone->zain="";
                    $phone->mtn=$number;
                    $phone->sudani="";
                    break;
                case "sudani":
                    $phone->zain="";
                    $phone->mtn="";
                    $phone->sudani=$number;
                    break;
            }
            $phone->save();
          //  $user_id=$user->id;
          //  $phone= Phone::find($user_id) ;
            $phones=[
                'zain'  => $phone->zain,
                'mtn'    => $phone->mtn,
                'sudani'  => $phone->sudani
            ];
			$responseData['error'] = false;
            $responseData['token'] = $token;
            $responseData['user'] = $user;
            $responseData['phone'] =$phones;
            $responseData['message'] = 'تم إنشاء الحساب بنجاح';

        } else {
            $responseData['error'] = true;
            $responseData['message'] = 'data has not saved';
        }

        return response()->json($responseData);
       // return response($response, 201);
    }

    public function login(Request $request) {
        $rules = array(
            'phone' => 'required|string',
            'password' => 'required|string'
        );
        $messsages = array(
            'phone.required'=>'You cant leave phone field empty',
            'password.required'=>'You cant leave password field empty',
//            'name.min'=>'The field has to be :min chars long',
        );
        $fields = $request->validate($rules,$messsages);
      /*  $fields = $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string'
        ]);*/
        // Check email

        $user = User::where('phone', $fields['phone'])->first();
if ($user==null){
    $responseData['error'] = true;
    $responseData['message'] = 'عفواً هذا الرقم غير مسجل';

    return response()->json($responseData);
    /*return response([
        'error' => true,
        'message' => 'عفواً هذا الرقم غير مسجل'
    ], 401);*/
}
        // Check password
        if(!$user || !Hash::check($fields['password'], $user->password)) {
             $responseData['error'] = true;
            $responseData['message'] = 'كلمة المرور غير صحيحة';

            return response()->json($responseData);
        }
        $user_id=$user->id;
        //$phone= Phone::find($user_id) ;
		$zain="";
        $mtn="";
		$sudani="";

        $phone = Phone::where('user_id', '=', "{$user_id}")->get();
		foreach ($phone as $article ){
		$zain=$article->zain;
				$mtn=$article->mtn;
		$sudani=$article->sudani;

		}
        $phones=[
            'zain'  => $zain,
            'mtn'    => $mtn,
            'sudani'  => $sudani
        ];
        $token = $user->createToken('myapptoken')->plainTextToken;
        $responseData['error'] = false;
        $responseData['user'] = $user;
        $responseData['phone'] =$phones;
        $responseData['token'] = $token;
        $responseData['message'] = 'تم التسجيل بنجاح';
        return response()->json($responseData);

    }

    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }
}
