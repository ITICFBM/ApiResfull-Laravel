<?php
/** Este modelo se extiende de manera directa del modelos user por medio de la herencia  */

namespace App;

use App\Transaction; 
use App\Scopes\BuyerScope;
use App\Transformers\BuyerTransformer;

/** Herencia de datos de user a buyer*/
class Buyer extends User
{
	/**public $transformer = BuyerTransformer::class;*/
	 /** el metodo boot es normal mente utilizado para constuir el modelo y lo utilizaremos para especificarle que scope utilizar
		* se hace un llamado al add globas scope*
	 */

	protected static function boot()
	{
		parent::boot();

		static::addGlobalScope(new BuyerScope);
	}

    public function transactions()
    {
		//una venta tiene muchas transacciones(hasMany)
    	return $this->hasMany(Transaction::class);
    }
}
