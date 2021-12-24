<?php

namespace App\Http\Controllers;

use App\Models\BuyHistory;
use App\Models\User;
use Illuminate\Http\Request;

class BuyCont extends Controller{
    public function buyScratch(Request $request){
        $request->validate([
            'user_id' => 'required',
            'amount' => 'required',
            'type' => 'required'
        ]);
        $user_id=$request->get("user_id");
        $amount=$request->get("amount");
        $type=$request->get("type");

        $user = User::find($user_id);
        if ($user==null){
            $responseData['error'] = true;
            $responseData['message'] = "User is not  found!!!";
            return response()->json($responseData);
        }

        $user_balance = $user->balance;
        if ($amount>$user_balance){
            $responseData['error'] = true;
            $responseData['message'] = "Your balance is not enough!!!";
            return response()->json($responseData);
        }
        $final_balance = $user_balance - $amount;
        $user->balance = $final_balance;
        if (!$user->save()) {
            $responseData['error'] = true;
            $responseData['message'] = "some error occurred!!!";
            return response()->json($responseData);

        }
        $scratch=random_int(1,9999999);
       $buyHis=new BuyHistory();
        $buyHis->user_id=$user_id;
        $buyHis->amount=$amount;
        $buyHis->scratch=$scratch;
        $buyHis->type=$type;

        if (!$buyHis->save()) {
            $responseData['error'] = true;
            $responseData['message'] = "data has not been found!!!";
            return response()->json($responseData);
        }

        $responseData['error'] = false;
        $responseData['message'] = " تمت تغزية حسابك بمبلغ " . $amount ." جنية بنجاح..." ;
        $responseData['amount'] = $amount;
        $responseData['balance'] = $final_balance;
        $responseData['scratch'] = $scratch;
        return response()->json($responseData);
    }
    public function buyBalance(Request $request){
        $request->validate([
            'user_id' => 'required',
            'amount' => 'required',
            'type' => 'required'
        ]);
        $user_id=$request->get("user_id");
        $amount=$request->get("amount");
        $type=$request->get("type");
        $number=$request->get("number");


        $user = User::find($user_id);
        if ($user==null){
            $responseData['error'] = true;
            $responseData['message'] = "User is not  found!!!";
            return response()->json($responseData);
        }

        $user_balance = $user->balance;
        if ($amount>$user_balance){
            $responseData['error'] = true;
            $responseData['message'] = "Your balance is not enough!!!";
            return response()->json($responseData);
        }
        $final_balance = $user_balance - $amount;
        $user->balance = $final_balance;
        if (!$user->save()) {
            $responseData['error'] = true;
            $responseData['message'] = "some error occurred!!!";
            return response()->json($responseData);

        }
        $scratch=random_int(1,9999999);
        $buyHis=new BuyHistory();
        $buyHis->user_id=$user_id;
        $buyHis->amount=$amount;
        $buyHis->scratch="";
        $buyHis->type=$type;

        if (!$buyHis->save()) {
            $responseData['error'] = true;
            $responseData['message'] = "data has not been found!!!";
            return response()->json($responseData);
        }

        $responseData['error'] = false;
        $responseData['message'] = " تمت تغزية حسابك بمبلغ " . $amount ." جنية بنجاح..." ;
        $responseData['amount'] = $amount;
        $responseData['balance'] = $final_balance;
        $responseData['scratch'] = $scratch;
        return response()->json($responseData);
    }
    public function displaySellHistory($user_id){
        $user = User::find($user_id);
        if ($user==null){
            $responseData['error'] = true;
            $responseData['message'] = "User is not  found!!!";
            return response()->json($responseData);
        }
        $all_data=$user->buys;
        //$all_data =  Balance::where('id', '=', "{$user_id}")->get();
        if ($all_data->count()<1){
            $responseData['error'] = true;
            $responseData['message'] = "There are no records!!!";
            return response()->json($responseData);
        }
		$all_articles=array();
		foreach ($all_data as $article ){
		$articles=[
                   // 'id'         => $article->id,
                    'amount'          => $article->amount,
                    'scratch'    => $article->scratch,
                    'date'            => $article->created_at,
                    'type'            => $article->type
                ];
				 array_push($all_articles,$articles);
		}
        $responseData['error'] = false;
        $responseData['sellHistories'] = $all_articles;
        return response()->json($responseData);

    }
    public function getUserBalance($id){
        $user = User::find($id);
        if ($user==null){
            $responseData['error'] = true;
            $responseData['message'] = "There are no records!!!";
            return response()->json($responseData);
        }
        return response()->json(['balance' => $user->balance]);
    }
}
