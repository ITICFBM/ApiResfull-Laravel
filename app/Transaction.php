<?php

namespace App;

use App\Buyer;
use App\Product;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\TransactionTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes; 

    public $transformer = TransactionTransformer::class;
    protected $dates = ['deleted_at'];
    protected $fillable = [
    	'quantity',
    	'buyer_id',
    	'product_id',
    ];

    public function buyer()
    {
        //una transacción tiene una (belongsTo) venta
    	return $this->belongsTo(Buyer::class);
    }

    public function product()
    {
        //una transacci{on pertenece a un (belongsTo) produto 
    	return $this->belongsTo(Product::class);
    }
}
