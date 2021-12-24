<?php

namespace App\Http\Controllers;

use App\Models\Balance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;
use function GuzzleHttp\Promise\all;

class SendingShowCont extends Controller{


    public function sentBalance($from_id){

        $user = User::find($from_id);
        if ($user==null){
            $responseData['error'] = true;
            $responseData['message'] = "User is not  found!!!";
            return response()->json($responseData);
        }
        $all_sent =  Balance::where('from', '=', "{$from_id}")->get();
        if ($all_sent->count()<1){
            $responseData['error'] = true;
            $responseData['message'] = "There are no records!!!";
            return response()->json($responseData);
        }
        $responseData['error'] = false;
        $responseData['balance'] = $all_sent;
        return response()->json($responseData);

    }
    public function receivedBalance($main_to_id){
        $user = User::find($main_to_id);
        if ($user==null){
            $responseData['error'] = true;
            $responseData['message'] = "User is not  found!!!";
            return response()->json($responseData);
        }
//        $all_sent = ([
//            ['from', '=', "{$to_id}"]||
//            ['to', '=', "{$to_id}"],
//
//        ]);
        $all_sent =  Balance::where('from', '=', "{$main_to_id}")->orWhere('to', '=', "{$main_to_id}")->get();
        if ($all_sent->count()<1){
            $responseData['error'] = true;
            $responseData['message'] = "There are no records!!!";
            return response()->json($responseData);
        }
        $final_articles=array();
      /*  foreach ($all_sent as $article ){
            $from_id=$article->from;
            $from_name=$article->user;
           // $from_name= User::where('id', '=', "{$to_id}")->get();
            $to_id=$article->to;
            //$to_name= User::find($to_id)->get()->name;
            $articles=[
                'from'         => $from_name,
                'to'          => $to_id,
                'amount'    => $article->amount,
                'scratch'            => $article->scratch,
                'date'    => Carbon::parse($article->publishedAt),
            ];
            array_push($final_articles,$articles);

        }*/

        foreach ($all_sent as $article ){
            $from_id=$article->from;
            $from_name= User::find($article->from)->name;
            $to_name = User::find($article->to)->name;
           // $name=$article->user;
          //  $user_id=$article->user->id;
            //$user_id=User::find($article->from);
          //  $from_name= User::find($article->from)->name;
           // $to_name = User::find($article->to)->name;
           // $to_name= User::find($to_id)->name;
            $type ="";
            if ($from_id==$main_to_id){
                $type="from";
                $name=$to_name;
            }else{
                $type="to";
                $name=$from_name;
            }
            $articles=[
                'name'         =>$name,
                'type'          => $type,
                'amount'          => $article->amount,
                'scratch'    => $article->scratch,
                'date'            => $article->created_at
            ];
            array_push($final_articles,$articles);
        }
        /*foreach ($all_sent as $article ){
            //  $from_id=$article->from;
            $to_id=$article->to;
            $from_name= User::find($article->from)->name;
            // $to_name = User::find($article->to)->name;
            // $to_name= User::find($to_id)->name;
            $articles=[
                //'from'         =>$to_name,
                'from'         =>$from_name,
                'to'          => User::find($to_id)->name,
                'type'          => User::find($to_id)->name,
                //'to'          => $to_id,
                'amount'          => $article->amount,
                'scratch'    => $article->scratch,
                'date'            => $article->created_at
            ];
            array_push($final_articles,$articles);
        }*/
        $responseData['error'] = false;
        $responseData['balance'] = $final_articles;
        return response()->json($responseData);

    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyBalance($id){
        $user = Balance::find($id);
        if ($user==null){
            $responseData['error'] = true;
            $responseData['message'] = "The record is not  found!!!";
            return response()->json($responseData);
        }
        $deletedBalance= Balance::destroy($id);
        if ($deletedBalance==null){
            $responseData['error'] = true;
            $responseData['message'] = "some error accrue!!!";
            return response()->json($responseData);
        }
        $responseData['error'] = false;
        $responseData['message'] = "The record has been deleted..." ;
        return response()->json($responseData);

    }


}
