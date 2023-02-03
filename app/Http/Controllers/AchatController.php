<?php

namespace App\Http\Controllers;

use App\Models\Achat;
use Illuminate\Http\Request;
use App\Models\Achat;

class AchatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
        $regle = array(            
           'numero_transaction',
            'mode_paiement',
            'detail_achat',
            'tiers',
            'compte_tresorerie',
            'controle_equilibre',
            'attache',
            'solde_tiers',
            'solde_tresorerie',
            'annalation',
            'date_annulation',
            'total_achat',
            'prod',
            'pu_Info',
            'qtAv',
            'qtAchat',
            'puReel',
            'totalTTC',
            'qtApres',
            'observation'
            );
        $validator = Validator::make($request->all(), $regle);
         
        if($validator->fails()){
          return response()->json($validator->errors(), 400);
        }
        
        
        $achat = Achat::create([
            'detail_achat' => ['prod'=>$request->prod , 'pu_Info'=>$request->pu_Info , 'qtAv'=>$request->qtAv , 'qtAchat'=>$request->qtAchat , 
            'puReel'=>$request->puReel , 'totalTTC'=>$request->totalTTC , 'qtApres'=>$request->qtApres , 'observation'=>$request->observation];
        ]);
        
        if($achat) {
        return response()->json([
        'status' => 'success',
        'data' => $achat
        ]);
        }
        return response()->json([
        'status' => 'fail',
        'message' => 'failed to create $achat record'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Achat  $achat
     * @return \Illuminate\Http\Response
     */
    public function show(Achat $achat)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Achat  $achat
     * @return \Illuminate\Http\Response
     */
    public function edit(Achat $achat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Achat  $achat
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Achat $achat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Achat  $achat
     * @return \Illuminate\Http\Response
     */
    public function destroy(Achat $achat)
    {
        //
    }
}
