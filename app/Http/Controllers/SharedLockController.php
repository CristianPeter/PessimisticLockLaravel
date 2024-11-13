<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SharedLockController extends Controller
{
    // SharedLock se utiliza para permitir que varias transacciones lean un
    public function sharedRead()
    {
        $producto = DB::transaction(function () {
            $producto = Product::query()->sharedLock()->find(1);
            dump($producto->stock);
            dump($producto->stock_value);
            sleep(10);
            dump($producto->stock);
            dump($producto->stock_value);
            return $producto;
        });
        return response()->json($producto);
    }
    // Si un proceso esta leyendo y el otro escribiendo,
    // ahi no hay problema, Si adquiere el lock el proceso que esta leyendo
    // las operaciones de modificación esperaran a que la transacción de lectura termine
    // Si adquiere el lock el proceso de escritura, y tiene transacciones de modificación
    // el proceso de lectura espera para poder leer el registro hasta que la transaccion termine
    public function sharedUpdate()
    {
        $producto = DB::transaction(function () {
            $producto = Product::query()->sharedLock()->find(1);
            dump($producto->stock);
            dump($producto->stock_value);
            sleep(10);
            $producto->stock += 1;
            $producto->stock_value += 1.1;
            $producto->save();
            dump($producto->stock);
            dump($producto->stock_value);
            return $producto;
        });
        return response()->json($producto);
    }
}
