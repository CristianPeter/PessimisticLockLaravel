<?php


use App\Http\Controllers\PessimisticController;
use App\Http\Controllers\SharedLockController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

// SharedLock vs LockForUpdate

Route::get('/sharedLock', function (PessimisticController $pessimisticController) {
    return $pessimisticController->lockShared();
});
Route::get('/lockForUpdate', function (PessimisticController $pessimisticController) {
    $pessimisticController->lockForUpdate();
});
Route::get('/regenerateDB', function (PessimisticController $pessimisticController) {
    productsFactory();
    return "regenerated";
});


// Shared Lock testing
// read
Route::get('/sharedLock/read', function (SharedLockController $sharedLockController){
   return $sharedLockController->sharedRead();
});
// update
Route::get('/sharedLock/update', function (SharedLockController $sharedLockController){
    return $sharedLockController->sharedUpdate();
});



function productsFactory()
{
    DB::table('table_products')->upsert([
        'nombre' => 'Producto de prueba 1',
        'stock' => 10,
        'stock_value' => 21.14
    ], 'nombre');
    DB::table('table_products')->upsert([
        'nombre' => 'Producto de prueba 2',
        'stock' => 5,
        'stock_value' => 12.18
    ], 'nombre');
    DB::table('table_products')->upsert([
        'nombre' => 'Producto de prueba 3',
        'stock' => 20,
        'stock_value' => 44.77
    ], 'nombre');
}