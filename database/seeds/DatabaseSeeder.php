<?php
/** Una ves ya definidos todos  los factorys debemos  hacer uso de ellos para insertar información 
 * *dentro de la base de datos donde esspecificaremos
 *  el numero de registro dentro de a funcion run */
use App\User;
use App\Product;
use App\Category;
use App\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** Desactivamos las inconcistencias de las llaves foraneas */
    	DB::statement('SET FOREIGN_KEY_CHECKS = 0');
/** Primero se tiene que borrar toda información que tengamos en la base de datos para insertar los registros nuevos con el metodo truncat eliminaos los registros
 * de las tablas. como no hay modelo para la tabla pivote se accede de manera directa por medio de DB para ello tenemos que usar la libreria.
 */
        User::truncate();
        Category::truncate();
        Product::truncate();
        Transaction::truncate();
        DB::table('category_product')->truncate();

        User::flushEventListeners();
        Category::flushEventListeners();
        Product::flushEventListeners();
        Transaction::flushEventListeners();
    
        /** debinimos la cantidad de registros que vamos a insertar en la BD */
        $cantidadUsuarios = 100;
        $cantidadCategorias = 30;
        $cantidadProductos = 100;
        $cantidadTransacciones = 100;

    
        /** Hacemos la llamada a los factory  y ytilizamos el metodo creat que lo que hace es crearlos a nivel BD */
        factory(User::class, $cantidadUsuarios)->create();
        factory(Category::class, $cantidadCategorias)->create();

        /** Al momento de crear un producto lo asociamos con una categoria  el metodo attach resive un array con toda la lista de los id de las categorias
         * que se le ingresara a cada uno de los productos
         */
		factory(Product::class, $cantidadTransacciones)->create()->each(
			function ($producto) {
                /** Aqui obtenemis todas las categorias aleatoriamente de 1 a 5 categorias y solo se necesita el id para eso utilizamos el metodo pluck y indica que solo queremos
                 * el id una vez que las obtenemos agregamos tales categorias a producto 
                 */
				$categorias = Category::all()->random(mt_rand(1, 5))->pluck('id');

				$producto->categories()->attach($categorias);
			}
		);        

        factory(Transaction::class, $cantidadTransacciones)->create();
    }
}