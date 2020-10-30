<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;
//use App\Http\Controllers\Controller;
/** en este controlador declararemos todos los metodos para el funcionamiento de la api */
class ApiController extends Controller
{
  /** para poder utilizar el traits lo usamos en api controller para que este se puede ocupar en todos nuestros controladores */

  /** Aqui ya podemos haceder a todos los metodos que estan en ApiResponser.php */
  Use ApiResponser;


}
