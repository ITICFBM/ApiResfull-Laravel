<?php

namespace App\Http\Controllers\Buyer;
use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

/**extendemos el controllador ApiController haciendo esto cual quier metodo que agregemos  en apicontroller
*podra ser utilizaco en cada uno de los controladores
*/
class BuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /**tenemos que optener unicamente  los compradores que tengan  compras esto es con el metodo 
         *has. este metodo recibe el nombre de un modelo que tenga relacion que es transactions*/
        $compradores = Buyer::has('transactions')->get();
        return $this->showAll($compradores);
        //return response()->json(['data' => $compradores], 200);

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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    //public function show(Buyer $buyer)
    {
        /** Mosramos un comprador que tenga al menos una compra */
        $comprador = Buyer::has('transactions')->findOrFail($id);
        return $this->showOne($comprador);
        //return $this->showOne($buyer);

        //return response()->json(['data'=> $comprador], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    /** se declara una funcion para el metodo globla sope   es una consulta  de manera global en 
     * *el modelo cada ves que se ejecuten consultas en el mismo */
}
