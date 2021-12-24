<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(){


        $all_articles=array();
        foreach (Service::all() as $article ){
            $articles=[
                'id'         => $article->id,
                'name'          => $article->name,
                'company'    => $article->company,
                'code'    => $article->code,
                'price'    => $article->price,
                'date'            => $article->created_at
            ];
            array_push($all_articles,$articles);
        }
        $responseData['error'] = false;
        $responseData['services'] = $all_articles;
        return response()->json($all_articles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'company' => 'required',
            'code' => 'required',
            'price' => 'required'


        ]);
        $service=Service::create($request->all());
        if ($service==null){
            $responseData['error'] = true;
            $responseData['message'] = "some error occurred!!!";
            return response()->json($responseData);
        }

        $responseData['error'] = false;
        $responseData['service'] = $service;
        $responseData['message'] = "service inserted successfully...";

        return response()->json($responseData);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
