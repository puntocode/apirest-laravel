<?php

namespace App\Models;

use App\Scopes\SellerScope;
use App\Transformers\SellerTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seller extends User
{
    use HasFactory;

    public $transform = SellerTransformer::class;

    protected static function boot(){
        parent::boot();
        static::addGlobalScope(new SellerScope);
    }

    #un vendedor tiene muchos productos
    public function products(){
        return $this->hasMany(Product::class);
    }
}
