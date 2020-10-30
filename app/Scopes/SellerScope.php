<?php
namespace App\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class SellerScope  implements Scope
{
  
  /** la funcion  apply() ejecuta el scope  y resive un buldier que e es el contructor de la consulta y el moidelo como tal*/

  public function apply( Builder $buldier, Model $model)
  {
    /** echo esto solo le debemos especificar al modelo buyer que cada ves 
     *que  vaya a ejecutar una consulta utilice este global scope en ella
     */
      $buldier->has('products');
  }
}
