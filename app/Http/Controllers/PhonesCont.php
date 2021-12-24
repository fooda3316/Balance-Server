<?php

namespace App\Http\Controllers;

use App\Models\Phone;
use App\Models\User;
use Illuminate\Http\Request;

class PhonesCont extends Controller{

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyAllPhones($id){
        $phone = Phone::find($id);
        if ($phone==null){
            $responseData['error'] = true;
            $responseData['message'] = "The Phone is not  found!!!";
            return response()->json($responseData);
        }
        $deletedPhone= Phone::destroy($id);
        if ($deletedPhone==null){
            $responseData['error'] = true;
            $responseData['message'] = "some error accrued!!!";
            return response()->json($responseData);
        }
        $responseData['error'] = false;
        $responseData['message'] = "All Phones have been deleted..." ;
        return response()->json($responseData);

    }
    public function updatePhone(Request $request){
        $request->validate([
            'user_id' => 'required',
            'zain' => 'required',
            'mtn' => 'required',
            'sudani' => 'required',
        ]);
        $user_id=$request->get("user_id");
        $type=$request->get("type");
        $number=$request->get("phone");
        $phones =  Phone::where('user_id', '=', "{$user_id}");
        if ($phones->get()->count()<1){
            $responseData['error'] = true;
            $responseData['message'] = "This Phone is not  found!!!";
            return response()->json($responseData);
        }
      

        $rsult= $phones->update($request->all());
        if ($rsult==null){
            $responseData['error'] = true;
            $responseData['message'] = "some error accrued!!!";
        }
        $sources =Phone::where([
            ['user_id', '=', "{$user_id}"]
        ])->get();
		$mtn="";
		$zain="";
		$sudani="";
		foreach ($sources as $source ){
			$mtn=$source->mtn;
			$zain=$source->zain;
			$sudani=$source->sudani;
		}
		$numbers=[
                    'zain'         => $zain,
                    'mtn'          => $mtn,
                    'sudani'    => $sudani];
        $responseData['error'] = false;
        $responseData['phone'] =  $numbers;
        $responseData['message'] = "The Phone has been updated...";
        return response()->json($responseData);
    }
}
