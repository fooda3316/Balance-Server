<?php

namespace App\Http\Controllers;

use App\Models\Balance;
use App\Models\BuyHistory;
use App\Models\User;
use App\Models\Phone;

use Illuminate\Http\Request;

class SendBalanceCont extends Controller{
    public function sendBalance(Request  $request){
        $request->validate([
            'from' => 'required',
            'to' => 'required',
            'amount' => 'required'
        ]);
        $from_id=$request->get("from");
        $to_id=$request->get("to");
        $amount=$request->get("amount");
        if ($from_id==$to_id){
            $responseData['error'] = true;
            $responseData['message'] = "You can not send balance to your self";
            return response()->json($responseData);
        }
        $from_user = User::find($from_id);
        $to_user = User::find($to_id);

        if ($from_user==null&&$to_user){
            $responseData['error'] = true;
            $responseData['message'] = "User is not  found!!!";
            return response()->json($responseData);
        }
        $user_from_balance = $from_user->balance;
        if ($amount>$user_from_balance){
            $responseData['error'] = true;
            $responseData['message'] = "Your balance is not enough!!!";
            return response()->json($responseData);
        }
        $user_to_balance = $to_user->balance;
        $user_to_final_balance = $user_to_balance + $amount;
        $to_user->balance = $user_to_final_balance;
        if (!$to_user->save()) {
            $responseData['error'] = true;
            $responseData['message'] = "some error occurred!!!";
            return response()->json($responseData);

        }
        $user_from_final_balance = $user_from_balance - $amount;
        $from_user->balance = $user_from_final_balance;
        if (!$from_user->save()) {
            $responseData['error'] = true;
            $responseData['message'] = "some error occurred!!!";
            return response()->json($responseData);

        }
        $balance=new Balance();
        $balance->from=$from_id;
        $balance->to=$to_id;
        $balance->amount=$amount;
        if (!$balance->save()) {
            $responseData['error'] = true;
            $responseData['message'] = "some error occurred!!!";

            return response()->json($responseData);

        }

        $responseData['error'] = false;
        $responseData['message'] = "Sending balance operation has been completed";
        $responseData['amount'] = $amount;
        return response()->json($responseData);
    }
	
	 public function lookForClint($phone){
	//	 $user= User::find($phone);
$user =User::where('phone', '=', "$phone");
$id=0;
$phone="";
$name="";
$image="";
$balance="";

 foreach ($user as $article ){
$id=$article->id;	
$phone=$article->phone;	 
$name=$article->name;	 
$image=$article->image;	 
$balance=$article->balance;	 
 
 }
 
  $result=[
            'id'          => $id,
            'phone'          => $phone,
			'name'          => $name,
			'image'    => $image,
            'balance'          => $balance

        ];
 // $user =  User::where('phone', '=', "{$phone}");
        if ($user->get()->count()<1){
		 //    if ($user==null){
            $responseData['error'] = true;
            $responseData['message'] = "عفواً هذا المستخدم غير موجود في التطبيق";
            return response()->json($responseData);
        }
		$responseData['error'] = false;
		//$responseData['user'] = $result;
        $responseData['user'] = $user->get();
		  $responseData['message'] = "تم العثور على المستخدم";
        return response()->json($responseData);
	 }
}
