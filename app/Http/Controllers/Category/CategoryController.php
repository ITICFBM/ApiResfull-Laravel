<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // select *ronn 
        $categories = Category::all();
        return $this->showAll($categories);
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
        $reglas =[
            'name' => 'required',
            'description' =>'required',
        ];

        $this->validate($request, $reglas);

        $category = Category::create($request->all());

        return $this->showOne($category,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    //public function show(Category $category)
    public function show(Category $category, $id)
    {
        $category = Category::findOrfail($id);
        return $this->showOne($category);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category, $id )
    {
        //$this->allowedAdminAction();
        /**al hacer una modificación debe de ser un dato diferente por ello ocupamos el metodo only e intersec solo optiene 
         *el nombre y la descripción**/
        $category = Category::findOrfail($id);
         $category->fill($request->only([
            'name',
            'description',
        ]));
       
        /**  el metodo isclean() verifica que la instancia o los datoso no hayan cambio de ser 
         * así retornamos un errors*
        */
       
        if($category->isClean()){
            return $this->errorResponse('Debes Especificar almenos un campo diferente', 422);
        }
        /** Si detatmos un cambio en datos procedemos a el metodo save() */

        $category->save();
        return $this->showOne($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category, $id)
    {
        //
        $category = Category::findOrfail($id);
        $category->delete();
        return $this->showOne($category, 200);
        
    }  
}
 