<?php
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\EmployeeAuthController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/ping', function (Request  $request) {    
    $connection = DB::connection("mongodb");
    //$connection = DB::connection(env("DB_CONNECTION_MONGO"));
    $msg = 'MongoDB is accessible!';
    try {  
        $connection->command(['ping' => 1]);  
            } catch (\Exception  $e) {  
        $msg = 'MongoDB is not accessible. Error: ' . $e->getMessage();
    }
    return ['msg' => $msg];
    });

Route::view('/navbar','/home/navbar');

Route::middleware(['auth:employee'])->group(function(){
    
    Route::view('/inicio','/home/main');

    
    Route::get('/categoria/registrar',[CategoryController::class, 'create']);
    Route::post('/categoria/registrar',[CategoryController::class, 'store']);
    Route::get('/categoria/lista',[CategoryController::class, 'index']);
    Route::get('/categoria/eliminar/{id}',[CategoryController::class, 'destroy']);
    Route::get('/categoria/lista/busqueda',[CategoryController::class, 'search'])->name('categorias.index.buscar');
    Route::get('/categoria/editar/{id}',[CategoryController::class, 'edit']);
    Route::put('/categoria/actualizar/{id}',[CategoryController::class, 'update']);
    
    Route::get('/producto/registrar',[ProductController::class, 'create']);
    Route::post('/producto/registrar',[ProductController::class, 'store']);
    Route::get('/producto/lista/categoria/{id}',[ProductController::class, 'indexByCategory']);
    Route::get('/producto/lista/en_orden/{order}',[ProductController::class, 'indexOrder']);
    Route::get('/producto/lista/busqueda',[ProductController::class, 'search'])->name('productos.index.buscar');
    Route::get('/producto/lista',[ProductController::class, 'index']);
    Route::delete('/producto/eliminar/{id}',[ProductController::class, 'destroy']);
    Route::get('/producto/mostrar/{id}',[ProductController::class, 'show']);
    Route::get('/producto/editar/{id}',[ProductController::class, 'edit']);
    Route::put('/producto/actualizar/{id}',[ProductController::class, 'update']);
    
    Route::get('/cliente/lista',[CustomerController::class, 'index']);
    Route::get('/cliente/lista/busqueda',[CustomerController::class, 'search'])->name('clientes.index.buscar');
    Route::get('/cliente/mostrar/{id}',[CustomerController::class, 'show']);
    Route::get('/cliente/compras/{id}',[CustomerController::class, 'ordersIndex']);
    Route::get('/cliente/compras/mostrar/{order_id}/{customer_id}',[CustomerController::class, 'orderShow']);
    
    Route::get('/compra/lista',[OrderController::class, 'index']);
    Route::get('/compra/mostrar/{id}',[OrderController::class, 'show']);
    Route::get('/compra/pedidos', [OrderController::class, 'getRecentOrders']);
    Route::get('/compra/pedidos/lista', [OrderController::class, 'indexPedidos']);
    Route::get('/compra/pedidos/entregado/{id}', [OrderController::class, 'deliver']);
    
    // Route::get('/empleado/registrar',[EmployeeController::class, 'create']);
    // Route::post('/empleado/registrar',[EmployeeController::class, 'store']);
    // Route::get('/empleado/lista',[EmployeeController::class, 'index']);
    // Route::get('/empleado/mostrar/{id}',[EmployeeController::class, 'show']);
    // Route::delete('/empleado/eliminar/{id}',[EmployeeController::class, 'destroy']);
    // Route::get('/empleado/editar/{id}',[EmployeeController::class, 'edit']);
    // Route::put('/empleado/actualizar/{id}',[EmployeeController::class, 'update']);
    // Route::get('/empleado/registrar',[EmployeeController::class, 'create']);
    
    Route::post('/cerrar_sesion', [EmployeeAuthController::class, 'logout']);
});

