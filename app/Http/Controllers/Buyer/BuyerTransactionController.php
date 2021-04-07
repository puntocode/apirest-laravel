<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        #Ruta compleja que muestra la lista de transacciones de ese comprador
        $transaction = $buyer->transactions;
        return $this->showAll($transaction);
    }


}
