<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     * http://apiresful/buyers
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $compradores = Buyer::has('transactions')->get();
        return $this->showAll($compradores);
    }


    /**
     * Display the specified resource.
     * http://apiresful/buyers/id
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Buyer $buyer)
    {
        //$compradores = Buyer::has('transactions')->findOrFail($id);
        return $this->showOne($buyer);
        //return response()->json(['data' => $compradores], 200);
    }


}
