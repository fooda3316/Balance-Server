<?php

namespace App\Http\Controllers;

use App\Models\Balance;
use App\Models\BuyHistory;
use App\Models\User;
use Illuminate\Http\Request;

class SendScratchCont extends Controller{
    public function sendScratch(Request  $request){
        $request->validate([
            'from' => 'required',
            'to' => 'required',
            'amount' => 'required',
            'scratch' => 'required'


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
        $scratch=$request->get("scratch");
        $balance=new Balance();
        $balance->from=$from_id;
        $balance->to=$to_id;
        $balance->amount=$amount;
        $balance->scratch=$scratch;

        if (!$balance->save()) {
            $responseData['error'] = true;
            $responseData['message'] = "some error occurred!!!";
            $responseData['scratch']=$scratch;

            return response()->json($responseData);

        }

        $responseData['error'] = false;
        $responseData['message'] = " تم إرسال مبلغ  " . $amount ." جنية بنجاح..." ;
        $responseData['amount'] = $amount;
        //$responseData['balance'] = $final_balance;
        return response()->json($responseData);
    }
}
