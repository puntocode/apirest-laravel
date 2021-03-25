<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    const PRODUCTO_DISPONIBLE = 'DISPONIBLE';
    const PRODUCTO_NO_DISPONIBLE= 'NO DISPONIBLE';

    protected $fillable = [
        'name',
        'description',
        'quantity',
        'status',
        'image',
        'seller_id',
    ];

    public function estaDisponible(){
        return $this->status == Product::PRODUCTO_DISPONIBLE;
    }

    #una producto tiene una relacion n:n con categoria tienendo asi en ambos modelos con belongsToMany
    public function categories(){
        return $this->belongsToMany(Category::class);
    }

    #un producto tiene muchas transacciones
    public function transactions(){
        return $this->hasMany(Seller::class);
    }

    #la relacion belongs to (pertenece a) se agrega en la Clase que lleva la llave foranea
    public function seller(){
        return $this->belongsTo(Seller::class);
    }

}
