<?php

namespace App;

use App\Seller;
use App\Category;
use App\Transaction;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\ProductTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
 
	const PRODUCTO_DISPONIBLE = 'disponible';
	const PRODUCTO_NO_DISPONIBLE = 'no disponible';

    public $transformer = ProductTransformer::class;
    protected $dates = ['deleted_at'];
    protected $fillable = [
    	'name',
    	'description',
    	'quantity',
    	'status',
    	'image',
    	'seller_id',
    ];
    protected $hidden = [
        'pivot'
    ];

    //por medio de esta fincion podemos estar llamando directamente a la funcion esta disponible en lugar de ponor si el status esta disponible
    public function isAvailable()
    {
    	return $this->status == Product::PRODUCTO_DISPONIBLE;
    }

    public function seller()
    {
        //un producto tiene un vendedor producto lleva la clave foreana de vendedor  belongsTo (pertenece a un)
        return $this->belongsTo(Seller::class);
    }

    public function transactions()
    {
        //un producto posee muchas transacciones es decir  un producto  esta en muchas transacciones hasMany(tiene much@s)
        return $this->hasMany('App\Transaction',Transaction::class);
    }


    public function categories()
    {
    //una producto  tiene una relación de muchos a muchos con categorias ->belongsToMany(pertenece a muchos)
        return $this->belongsToMany('App\Category',Category::class);
    }
}
