<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $categories = $buyer->transactions()
            ->with('products.categories')
            ->get()
            ->pluck('products.categories')
            ->unique('id')
            ->values(); #elimina espacios vacios en la data

            return $this->showAll($categories);
    }


}
