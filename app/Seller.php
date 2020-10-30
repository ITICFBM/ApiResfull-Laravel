<?php
/** Este modelo se extiende de manera directa del modelos user por medio de la herencia  */
namespace App;
use App\Product;
use App\Scopes\SellerScope;
//use App\Transformers\SellerTransformer;

/** Herencia de datos de user a buyer*/
class Seller extends User
{
	//public $transformer = SellerTransformer::class;
 
	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope(new SellerScope);
	 
	}

  public function products()
    {
		//un vendedor tienen (has many) muchos productos 
    	return $this->hasMany(Product::class);
    }
}
 