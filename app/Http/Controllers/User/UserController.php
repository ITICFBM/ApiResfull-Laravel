<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Mail\UserCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Transformers\UserTransformer;
use App\Http\Controllers\ApiController;

class UserController extends ApiController
{
    public function __construct()
    {
        //$this->middleware('client.credentials')->only(['store', 'resend']);
        //$this->middleware('auth:api')->except(['store', 'verify', 'resend']);
        //$this->middleware('transform.input:' . UserTransformer::class)->only(['store', 'update']);
        //$this->middleware('scope:manage-account')->only(['show', 'update']);
        //$this->middleware('can:view,user')->only('show');
        //$this->middleware('can:update,user')->only('update');
        //$this->middleware('can:delete,user')->only('destroy');
    } 
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //s$this->allowedAdminAction();
        
        //listamos todos lo susuarios 
        $usuarios = User::all();
        //return response()->json(['data'=> $usuarios,200]);
        return $this->showAll($usuarios);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        /** Aqui dependemos de hendler y de apicontroller */
    /*     $campos = $request->all();
         $usuario = User::create($campos);
         return response()->json(['data' => $usuario],201)
        
 */
        //creamos reglas de validación
        $reglas = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ];
            // laravel genera una excepción
        $this->validate($request, $reglas);

        //asignación masiva
        $campos = $request->all();   
        $campos['password'] = bcrypt($request->password);
        $campos['verified'] = User::USUARIO_NO_VERIFICADO;
        //verificar correo o cuenta
        $campos['verification_token'] = User::generarVerificationToken();
        $campos['admin'] = User::USUARIO_REGULAR;

        $usuario = User::create($campos);
        
        //return response()->json(['data' => $usuario],201);

       return $this->showOne($usuario);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /* #Inyección de Modelos de manera implicita.*/
    //public function show(User $user)
    public function show($id) 
    {
        $usuario = User::findOrfail($id);
        //dd($usuario);
        //return response()->json(['usuario' => $usuario],200);
        return $this->showOne($usuario);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   
     //public function update(Request $request, User $user)
    public function update(Request $request,$id)
    {
        $user = User::findOrfail($id);
        $reglas = [
            /**  tenemos una validación del email y si ese email es el mismo con el que nos logeamos la validación va a falla debemos hacer una excepción
             *  del usuario con el que nos estamos logeando  ' . $user->id,
            */
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'min:6|confirmed',
            //con in veriicamos que este en uno de estos dos valores
            'admin' => 'in:' . User::USUARIO_ADMINISTRADOR . ',' . User::USUARIO_REGULAR,
        ];

        $this->validate($request, $reglas);

        if ($request->has('name')) {
            $user->name = $request->name;
        }
        // si el correo es diferente al que esta entonces cambiamos el status a un status no  verificado
        if ($request->has('email') && $user->email != $request->email) {
            $user->verified = User::USUARIO_NO_VERIFICADO;
            $user->verification_token = User::generarVerificationToken();
            $user->email = $request->email;
        }
        // si cambiamos la pass entonces encriptamos la contraseña
        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        if ($request->has('admin')) {
            //$this->allowedAdminAction();
        /**si el usuario no es verificado mandamos un error con alerta 409 es un error de conflicto */
            if (!$user->esVerificado()) {
                //return response()->json(['error'=>'Unicamente los usuarios verificados pueden cambiar su valor de administrador','code' => 409],409);
                /** utilizamos el metodo errorResponse para las respuestas de error */
                return $this->errorResponse('Unicamente los usuarios verificados pueden cambiar su valor de administrador', 409);
            }

            $user->admin = $request->admin;
        }
        /**si el usuario no cambia nada el metodo Dirty valida que almenos un campo debe ser diferente el error 422 es una petición mal formada*/
        if (!$user->isDirty()) {
            
            //return response()->json(['error'=>'Se debe especificar al menos un valor diferente para actualizar','code' => 422],422);

            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', 422);
        }
        

        $user->save();
         //return response()->json(['data'=> $user], 200);
       return $this->showOne($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     //public function destroy(User $user)
         public function destroy($id)
    {
        $user = User::findOrfail($id);
        $user->delete();
        //return response()->json(['data'=> $user], 200);
        return $this->showOne($user);
    }

/*     public function me(Request $request)
    {
        $user = $request->user();
        
        return $this->showOne($user);
    } */

/*     public function verify($token)
    {
        $user = User::where('verification_token', $token)->firstOrFail();

        $user->verified = User::USUARIO_VERIFICADO;
        $user->verification_token = null;

        $user->save();

        return $this->showMessage('La cuenta ha sido verificada');
    } */

/*     public function resend(User $user)
    {
        if ($user->esVerificado()) {
            return $this->errorResponse('Este usuario ya ha sido verificado.', 409);
        }

        retry(5, function() use ($user) {
            Mail::to($user)->send(new UserCreated($user));
        }, 100);

        return $this->showMessage('El correo de verificación se ha reenviado');

    } */
}
