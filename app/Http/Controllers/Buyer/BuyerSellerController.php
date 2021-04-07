<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerSellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $buyers = $buyer->transactions()
            ->with('products.seller')
            ->get()
            ->pluck('products.seller')
            ->collapse() #junta todo en un array
            ->unique('id')
            ->values(); #elimina espacios vacios en la data

            return $this->showAll($buyers);
    }


}
