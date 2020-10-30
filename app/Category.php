<?php

namespace App;

use App\Product;
use Illuminate\Database\Eloquent\Model;
use App\Transformers\CategoryTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{ 
	use SoftDeletes;
	
    public $transformer = CategoryTransformer::class;
	protected $dates = ['deleted_at'];
    protected $fillable = [
    	'name',
    	'description',
    ];

    protected $hidden = [
        'pivot'
    ];


    public function products()
    {
        //una categoria  tiene una relación de muchos a muchos con productos ->belongsToMany(pertenece a muchos)
    	return $this->belongsToMany('App\Product',Product::class);
    }
}
