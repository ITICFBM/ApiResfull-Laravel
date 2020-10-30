<?php

namespace App;

//use App\Transformers\UserTransformer;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, /*HasApiTokens*/SoftDeletes;
// 1  es igual a si el usuario ya esta ferificado y 0 es que no esta verificado 
    const USUARIO_VERIFICADO = '1';
    const USUARIO_NO_VERIFICADO = '0';

    const USUARIO_ADMINISTRADOR = 'true';
    const USUARIO_REGULAR = 'false';

    //public $transformer = UserTransformer::class;

    //utilizamos el nombre de la tabla y especificamos en que tabla se tiene que ederar y laravel sabe que se utiliza de manera implicita
    protected $table = 'users';
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //un usuario tiene estos campos 
    protected $fillable = [
        'name',
        'email',
        'password',
        'verified',
        'verification_token',
        'admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    //oculta los atributos que estan incluidos ahi al momento de convertir la representacion de este modelo en un array primero es array->json
    //por lo tanto todo lo que este en hidden no se mostrara en  el json de nuestra api 
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    /** esta funcion es un mutador en laravel esto hace que cambie los datos antes de ser guardados en la base de datos ya que todos los datos deben ser
    * en mayusculas o en minusculas */
    public function setNameAttribute($valor)
    {
        /**strtolower es el metodo que hace que todo este en minusculas */
        $this->attributes['name'] = strtolower($valor);
    }

    /** asesores se definen con get y obtenemos el valor del atributo esto no es un update 
     *  simplemente se retorna una transformación  pero dicha transformación no se hace efectiva al valor  original simplemente se transforma el valor
     *  sin necesidad de transformarlo  ejecutamos php artisan migrate:refresh --seed
     */
    public function getNameAttribute($valor)
    {
        /** ucwords pone la primera letra en mayusculas si queremos que la primera letra sea A de cala letra es ucwords */
        return ucwords($valor);
    }

    public function setEmailAttribute($valor)
    {
        $this->attributes['email'] = strtolower($valor);
    }

    /** Fucticion o metodo para saber si el usuario esta verificado o no */
    public function esVerificado()
    {
        /**valor correspondiente a comparar */
        return $this->verified == User::USUARIO_VERIFICADO;
    }

/** Función o metodo para seber si es administrador o no  */
    public function esAdministrador()
    {
        return $this->admin == User::USUARIO_ADMINISTRADOR;
    }
/** Función para obtener un token de verificación */
    public static function generarVerificationToken()
    {
        /** El token se genera de maanera aleatoria y tienen un longitud de 40 caracteres los recomendables es de 24 asi adelante
         * esto con el fin de que no se desifrado facilmente de manera programatica o manual 
        */
        return Str::random(40);
    }
}
