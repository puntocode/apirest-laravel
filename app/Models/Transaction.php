<?php

namespace App\Models;

use App\Transformers\TransactionTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    public $transform = TransactionTransformer::class;

    protected $fillable = [
        'quantity',
        'buyer_id',
        'product_id',
    ];

    #una transaccion pertenece a un producto
    public function products(){
        return $this->belongsTo(Product::class, 'product_id');
    }

    #una transaccion pertenece a un comprador
    public function buyers(){
        return $this->belongsToMany(Buyer::class);
    }


}
