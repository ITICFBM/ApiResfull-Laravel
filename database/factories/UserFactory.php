<?php

use App\Category;
use App\Product;
use App\Seller;
use App\Transaction;
use App\User;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/
/** faker es una libreria para crear datos aleatorios esto para tener registros  falsos y generara datos falso  o  aleatoreos en nuestra base de datos */

/** @var \Illuminate\Database\Eloquent\Factory $factory */

/** Factory para modelo user */
$factory->define(User::class, function (Faker\Generator $faker) {
    static $password;

    return [       
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        //bycrypt incripta la pass en la base de datos 
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => Str::random(10),
        //** el kaker solo va osilar entre nuestras nos constantes que tenemos verificado y us.no verificado */
        'verified' => $verificado = $faker->randomElement([User::USUARIO_VERIFICADO, User::USUARIO_NO_VERIFICADO]),
        //** generamos el token de verificacion esto se genera si el usuario esta o no verificado es decir si iel usuario esta verificado 
        //no deberia tener un token de verificaci{on y si no esta verificado deberia tener el token }. */
        'verification_token' => $verificado == User::USUARIO_VERIFICADO ? null : User::generarVerificationToken(),
        'admin' => $faker->randomElement([User::USUARIO_ADMINISTRADOR, User::USUARIO_REGULAR]),
    ];
});

/** factory para modelo category */
$factory->define(Category::class, function (Faker\Generator $faker) {
    return [
        /** word va inserta palabras con faker */
        'name' => $faker->word,
        /**creamos parrafos utilizando faker y especifico que solo se 1 parrafo */
        'description' => $faker->paragraph(1),
    ];
});

/** factory para modelo productos */
$factory->define(Product::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
        /**qnty aleatoreos por medio de la funcion numberbetween entre 1 y 10*/
        'quantity' => $faker->numberBetween(1, 10),
        'status' => $faker->randomElement([Product::PRODUCTO_DISPONIBLE, Product::PRODUCTO_NO_DISPONIBLE]),
        //** Creamos una carpeta con 3 imagenes y la metemos en public y las renombramos ocn 1,2,3*/
        'image' => $faker->randomElement(['1.jpg', '2.jpg', '3.jpg']),
        /**'seller_id' => User::inRandomOrder()->first()->id, */
        /**optenemos todos los usuarios y insertamos uno aleatoreo y solo pasamos el id**/
        'seller_id' => User::all()->random()->id,
    ];
});

/** Factory para transactions */
$factory->define(Transaction::class, function (Faker\Generator $faker) {
    /**optenemos todos lo susuarios que tengan almenos un producto */
    $vendedor = Seller::has('products')->get()->random();
    /** optenemos los compradores puede ser cualquier usuarios aqui no importa si ya comproo o no ha comprado  */
    /** vamos a obtener un comprador excepto el vendedir que ya obtuvimos en la linea 72 se obtine de forma aleatoria*/
    $comprador = User::all()->except($vendedor->id)->random();

    return [
        /**Cantidad entre 1 y 3  */
        'quantity' => $faker->numberBetween(1, 3),
        'buyer_id' => $comprador->id,
        /** obtenemos un vendedor y con la lista de productos y obtenemos un producto aleatorio */
        'product_id' => $vendedor->products->random()->id,
    ];
});
