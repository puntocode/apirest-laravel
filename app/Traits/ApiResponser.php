<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

trait ApiResponser{

    private function successResponse($data, $code){
        return response()->json($data, $code);
    }

    protected function errorResponse($message, $code){
        return response()->json(['error' => $message, 'code' => $code ], $code);
    }

    protected function showAll(Collection $collection, $code = 200){
        if($collection->isEmpty()){
            return $this->successResponse($collection, 200);
        }

        $transformer = $collection->first()->transform;

        $collection = $this->filterData($collection, $transformer);
        $collection = $this->sortData($collection, $transformer);
        $collection = $this->transformData($collection, $transformer);

        return $this->successResponse(['data' => $collection], $code);
    }

    protected function showOne(Model $instance, $code = 200){
        return $this->successResponse(['data' => $instance], $code);
    }

    protected function successMessage($message, $code=200){
        return $this->successResponse($message, $code);
    }

    #TRANSFORMA LOS NOMBRES DE LOS VALORES
    protected function transformData($data, $transformer){
        $transformation = fractal($data, new $transformer);
        return $transformation->toArray();
    }

    #ORDENA DE ACUERDO LO PIDAN -> ?sort_by=nombre
    protected function sortData(Collection $collection, $transformer){
        if(request()->has('sort_by')){
            $attribute = $transformer::originalAttribute(request()->sort_by);
            $collection = $collection->sortBy->{$attribute};
        }
        return $collection;
    }

    #FILTRADO DE DATOS -> ?esVerificado=1
    protected function filterData(Collection $collection, $transformer){
        foreach(request()->query() as $query => $value){
            $attribute = $transformer::originalAttribute($query);
            if(isset($attribute, $value)){
                $collection = $collection->where($attribute, $value);
            }
        }
        return $collection;
    }

}
