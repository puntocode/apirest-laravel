<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    #una categoria tiene una relacion n:n con productos tienendo asi ambos modelos con belongsToMany
    public function products(){
        return $this->belongsToMany(Product::class);
    }

}
