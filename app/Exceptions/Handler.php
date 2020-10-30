<?php

namespace App\Exceptions;

use Exception;
use App\Traits\ApiResponser;
use Illuminate\Database\QueryException;
/** Importamos validationException */
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /** Utilizamos el traits */
    use ApiResponser;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
        \Illuminate\Auth\AuthenticationException::class,
         \Illuminate\Auth\Access\AuthorizationException::class,
          \Symfony\Component\HttpKernel\Exception\HttpException::class,
          \Illuminate\Database\Eloquent\ModelNotFoundException::class,
          \Illuminate\Session\TokenMismatchException::class,
          Illuminate\Validation\ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        /**  No podemos depender de render para manejaar diferente tipo de excepciones que pueden surgir en nuestra apirresfull
         * lo cual es que da demasiados detalles y no es adecuado para cuando uno este en producción para ello agregamos un if 
         * preguntando si la excepcion es una instancia de valite exception 
         */
  if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        /** condicion para retonar un error en format json cuando no se encuentre un usuario */
        if($exception instanceof ModelNotFoundException) {
            /** Las excepciones de model nos proporcionan acesso al modelo  esto nos sirve para class_basename sirve para no pasar app//user strtolower minusculas */
            //$modelo = $exception->getModel();
            $modelo = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse("No existe ningun registro en {$modelo} con el id esecificado",404);
        }
        /** Funcion para las exception de atenticaci{on de usuarios} laravel mediante el metodo render hace un llamdo a unauthenticated*/
        if ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }
        /** Funcion para definir aciones que puede realizar cada tipo de usuario */
        if ($exception instanceof AuthenticationException) {
            return $this->errorResponse("No posee permisos para ejecutar esta acción",403);
        }
        /** if para generar un error en json de no found http */
        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse("No se ejecuto la URL especificada",404);
        }

        /** funcion para generrar un message error cuando el metodo put post no corresponde a la solicitud enviada esto es cuando pasa?
         * cuando un metodo no es soportado
         */
        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse("El metodo especificado no es valido ",405);
        }
        /** No es recomendable agregar varias condiciones para cada uno de los errores que pueden ocurrir en nuestra api. 
         * por ello controlaremos de manera general cualquiera definicion http obtenemos el msj obtenemos el status*/
        if($exception instanceof HttpException){
          return $this->errorResponse($exception->getMessage(),$exception->getStatusCode());

        }
        /** Con query exception logramos no mandar el error 1451 este error ocurre cuando no puedes borrar un registro que tiene una llave foranea*/
        if($exception instanceof QueryException){
            //dd($exception);
          $codigo = $exception ->errorInfo[1];

            if($codigo == 1451){
                 return $this->errorResponse("No se puede eliminar de forma permanente el recuros por que esta relacionado con algún otro",409);

            }

        }
        /** Si estamos en modo depuración retornamos el error en html preguntamos por el debug de laravel sino  */
        if(config('app.debug')){
            return parent::render($request, $exception);

        }
        /** Funcion  para errores 500 si el server se cayo, si la conexi{on a la base de datos falla 
         * *etc para producción app_debug = false .env */
            return $this->errorResponse('Falla inesperada. Intente Más Tarde',500);
            return parent::render($request, $exception);
    }

    protected function unauthenticated($request , AuthenticationException $exception){

        return $this->errorResponse('No autenticado.', 401);
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();
        /** Utilizamos el metofo error Response de traits */
        return $this->errorResponse($errors,422);
        //return response()->json($errors, 422);
    }
}
