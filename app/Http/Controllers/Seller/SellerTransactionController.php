<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Seller;
use Illuminate\Http\Request;

class SellerTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        #lista de transacciones de un vendedor especifico
        $transactions = $seller->products()
        ->whereHas('transactions')
        ->with('transactions')
        ->get()
        ->pluck('transactions')
        ->collapse();

        return $this->showAll($transactions);
    }


}
