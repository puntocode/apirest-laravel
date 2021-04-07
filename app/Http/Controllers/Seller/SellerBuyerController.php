<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerBuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        #lista de compradores de un vendedor especifico
        $buyers = $seller->products()
            ->whereHas('transactions')
            ->with('transactions.buyers')
            ->get()
            ->pluck('transactions')
            ->collapse()
            ->pluck('buyers')
            ->unique()
            ->values();

        return $this->showAll($buyers);
    }


}
