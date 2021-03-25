<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class BuyerScope implements Scope{
    #LOS ESCOPE SON FUNCIONES GLOBALES PARA CONTROLADORES

    public function apply(Builder $builder, Model $model)
    {
        $builder->has('transactions');
    }
}
