<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        #pluck nos permite trabajar o operar directamente sobre esa coleccion en este caso solo producto
        $products = $buyer->transactions()->with('products')->get()->pluck('products');
        return $this->showAll($products);
    }


}
