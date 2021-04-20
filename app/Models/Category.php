<?php

namespace App\Models;

use App\Transformers\CategoryTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public $transform = CategoryTransformer::class;

    protected $fillable = [
        'name',
        'description'
    ];

    #una categoria tiene una relacion n:n con productos tienendo asi ambos modelos con belongsToMany
    public function products(){
        return $this->belongsToMany(Product::class);
    }

    #oculta los resultados pivot{ }
    protected $hidden = [
        'pivot'
    ];

}
