<?php

namespace App\Http\Controllers;

use App\Models\Balance;
use App\Models\ChargeHistory;
use App\Models\ChargesHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
class ChargeCont extends Controller
{

    public function saveChargeOp($id)
    {
//        $data = Http::get('http://localhost:90/Slim/home/chargeOp');
//        $id = $data->offsetGet("id");
//        $error = $data->offsetGet("error");
//        $amount = $data->offsetGet("amount");
        // $id = 1;
        $error = false;
        $amount = 500;
        $chargeHis = new ChargesHistory();

        $user = User::find($id);
        if ($user == null || $error) {
            $responseData['error'] = true;
            $responseData['message'] = "User has not been found!!!";
            return response()->json($responseData);
        }

        $user_balance = $user->balance;
        $final_balance = $user_balance + $amount;
        $user->balance = $final_balance;
        if (!$user->save()) {
            $responseData['error'] = true;
            $responseData['message'] = "User has not been found!!!";

        }
        $chargeHis->user_id = $id;
        $chargeHis->amount = $amount;
        if (!$chargeHis->save()) {
            $responseData['error'] = true;
            $responseData['message'] = "data has not been found!!!";

        }
        $responseData['error'] = false;
        $responseData['message'] = " تمت تغزية حسابك بمبلغ " . $amount ." جنية بنجاح..." ;
        $responseData['balance'] = $final_balance;
        $responseData['amount'] = $amount;

        return response()->json($responseData);
    }
    public function chargeOtherClint(Request $request){
        $request->validate([
            'user_id' => 'required',
            'amount' => 'required',
            'clint_id' => 'required'
        ]);

        $user_id=$request->get("user_id");
        $clint_id=$request->get("clint_id");
        $amount=$request->get("amount");
        $user=User::find($user_id);
        $clint=User::find($clint_id);
        if ($user==null||$clint==null){
            $responseData['error'] = true;
            $responseData['message'] = "User is not  found!!!";
            return response()->json($responseData);
        }
        $user_balance = $user->balance;
        $clint_balance = $clint->balance;
        if ($amount>$user_balance){
            $responseData['error'] = true;
            $responseData['message'] = "عفواً رصيدك غير كافي لإجراء هذة العملية";
            return response()->json($responseData);
        }
        $final_user_balance = $user_balance - $amount;
        $user->balance = $final_user_balance;
        $final_clint_balance = $clint_balance + $amount;
        $clint->balance = $final_clint_balance;
        if (!$user->save()) {
            $responseData['error'] = true;
            $responseData['message'] = "some User error occurred!!!";
            return response()->json($responseData);
        }
        if (!$clint->save()) {
            $responseData['error'] = true;
            $responseData['message'] = "some Clint error occurred!!!";
            $responseData['final Clint'] = $final_clint_balance;
            $responseData[' Clint'] = $clint_balance;

            return response()->json($responseData);
        }
        $ChargeHistory=new ChargeHistory();
        $ChargeHistory->user_id=$user_id;
        $ChargeHistory->clint_id=$clint_id;
        $ChargeHistory->amount=$amount;
        $ChargeHistory->save();
        $responseData['error'] = false;
        $responseData['message'] = " تم إرسال مبلغ " . $amount ." جنية بنجاح..." ;
        $responseData['amount'] = $amount;
        $responseData['balance'] = $final_user_balance;
        return response()->json($responseData);
    }
    public function displayChargeHistory($user_id){
        $user = User::find($user_id);
        if ($user==null){
            $responseData['error'] = true;
            $responseData['message'] = "User is not  found!!!";
            return response()->json($responseData);
        }
        $all_data =  ChargeHistory::where('user_id', '=', "{$user_id}")->orWhere('clint_id', '=', "{$user_id}")->get();
        if ($all_data->count()<1){
            $responseData['error'] = true;
            $responseData['message'] = "There are no records!!!";
            return response()->json($responseData);
        }
        //$all_data =  Balance::where('id', '=', "{$user_id}")->get();
        if ($all_data->count()<1){
            $responseData['error'] = true;
            $responseData['message'] = "There are no records!!!";
            return response()->json($responseData);
        }
        $all_articles=array();

        foreach ($all_data as $article ){
            $from_id=$article->clint_id;
            //$type="";
           // $name= User::find($article->clint_id)->name;
            $user_name= User::find($article->user_id)->name;
            $clint_name = User::find($article->clint_id)->name;
          //  $clint_name = User::find($article->clint_id)->name;
            if ($from_id==$user_id){
                $type="from";
                $name=$clint_name;
            }else{
                $type="to";
                $name=$user_name;
            }
            $articles=[
                // 'id'         => $article->id,
                'amount'          => $article->amount,
                'name'    => $name,
                'type'    => $type,
                'date'            => $article->created_at
            ];
            array_push($all_articles,$articles);
        }
        $responseData['error'] = false;
        $responseData['chargeHistories'] = $all_articles;
        return response()->json($responseData);

    }
	public function displayChargeOp($user_id){
        $user = User::find($user_id);
        if ($user==null){
            $responseData['error'] = true;
            $responseData['message'] = "User is not  found!!!";
            return response()->json($responseData);
        }
        $all_data =  ChargesHistory::where('user_id', '=', "{$user_id}")->get();
        if ($all_data->count()<1){
            $responseData['error'] = true;
            $responseData['message'] = "There are no records!!!";
            return response()->json($responseData);
        }
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
                'date'            => Carbon::parse($article->created_at)
            ];
            array_push($all_articles,$articles);
        }
        $responseData['error'] = false;
        $responseData['chargeOp'] = $all_articles;
        return response()->json($responseData);

    }
}
