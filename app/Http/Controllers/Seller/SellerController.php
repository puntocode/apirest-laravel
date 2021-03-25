<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     * http://apiresful/sellers
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendedores = Seller::has('products')->get();
        return response()->json(['data' => $vendedores], 200);
    }




    /**
     * Display the specified resource.
     * http://apiresful/sellers/id
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Seller $seller)
    {
        //$vendedores = Seller::has('products')->findOrFail($id);
        return response()->json(['data' => $seller], 200);
    }


}
