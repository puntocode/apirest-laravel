<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        #lista de productos de un vendedor especifico
        $products = $seller->products;
        return $this->showAll($products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $seller){
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'quantity' => 'required|integer|min:1',
            'image' => 'required|image',
        ];

        $this->validate($request, $rules);

        $data = $request->all();

        $data['status'] = Product::PRODUCTO_NO_DISPONIBLE;
        #store es un metodo para guardar archivos definidos en el fylesystem
        #store recibe la ruta donde se guardara pero en este caso no necesitamos porque ya definimos en el fylesystem.
        $data['image'] = $request->image->store('storage/uploads','public');
        $data['seller_id'] = $seller->id;
        //dd($data);

        $product = Product::create($data);

        return $this->showOne($product, 201);
    }



    /**
     * Update the specified resource in storage.
     * http://127.0.0.1:8000/sellers/3/products/11 con form-data method->put para subir imagen
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller, Product $product)
    {
        $rules = [
            'status' => 'in:'. Product::PRODUCTO_DISPONIBLE .','. Product::PRODUCTO_NO_DISPONIBLE,
            'quantity' => 'integer|min:1',
            'image' => 'image',
        ];

        $this->validate($request, $rules);

        if($seller->id != $product->seller_id){
            return $this->errorResponse('El vendedor especificado ne es el vendedor del producto', 422);
        }

        /*$product->fill($request->intersect([
            'name',
            'description',
            'quantity'
        ]));*/

        $product->fill($request->only('name', 'description', 'quantity'));
        //dd($product);

        if($request->has('status')){
            $product->status = $request->status;
            #comprobamos que haya al menos una categoria para poner a disponible
            if($product->estaDisponible() && $product->categories()->count() == 0){
                return $this->errorResponse('Un producto activo debe tener al menos una categoria', 409);
            }
        }

        if($request->hasFile('image')){
            Storage::disk('public')->delete($product->image);
            $product->image = $request->image->store('storage/uploads','public');
        }

        if($product->isClean()){
            return $this->errorResponse('Se debe especificar al menos un valor diferente para acutalizar', 422);
        }

        $product->save();

        return $this->showOne($product);
    }

    /**
     * Remove the specified resource from storage.
     * http://127.0.0.1:8000/sellers/3/products/id method delete
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller, Product $product)
    {
        $this->verificarVendedor($seller, $product);
        Storage::disk('public')->delete($product->image);
        //Storage::delete('storage/uploads/FKHzNY8xSpqJVB6D6MSm4N64K236eLtUPF7p15GF.jpg');
        $product->delete();
        return $this->showOne($product);
    }

    protected function verificarVendedor(Seller $seller, Product $product){
        if($seller->id != $product->seller_id){
            throw new HttpException(422, 'El vendedor especificado ne es el vendedor del producto');
        }
    }


}
