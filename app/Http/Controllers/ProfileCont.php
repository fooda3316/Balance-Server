<?php

namespace App\Http\Controllers;

use App\Models\Phone;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileCont extends Controller
{
    public function showProfile($id){
        $phone= Phone::find($id) ;
        $user = User::find($id);
        if ($user==null){
            $responseData['error'] = true;
            $responseData['message'] = "User is not  found!!!";
            return response()->json($responseData);
        }
        if ($phone==null){
            $responseData['error'] = true;
            $responseData['message'] = "Phone is not  found!!!";
            return response()->json($responseData);
        }
        $result=[
            'id'          => $phone->user_id,
            'name'          => $user->name,
            'balance'          => $user->balance,
            'zain'          => $phone->zain,
            'mtn'    => $phone->mtn,
            'sudani'    => $phone->sudani

        ];
        $responseData['error'] = false;
        $responseData['profile'] = $result;
        return response()->json($responseData);
    }
}
