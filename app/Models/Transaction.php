<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'quantity',
        'buyer_id',
        'product_id',
    ];

    #una transaccion pertenece a un producto
    public function products(){
        return $this->belongsTo(Product::class);
    }

    #una transaccion pertenece a un comprador
    public function buyers(){
        return $this->belongsToMany(Buyer::class);
    }


}
