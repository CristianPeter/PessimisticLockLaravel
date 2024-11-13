<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class PessimisticController extends Controller
{
    public function lockShared()
    {
        // Si dos procesos ejecutan esta transacción al mismo tiempo
        // se producira un deadlock dado que ninguno puede modificar el producto
        // con id 1 hasta que alguno suelte el lock
        // La utilidad de sharedLock es para asegurarse de que los datos leidos no
        // son sucios, dado que el dato que tu has leido no lo puede modificar nadie hasta que termine tu
        // transaccion
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

    // Esto evita el deadlock dado que el segundo proceso no podra leer los datos
    // del producto con id 1 hasta que termine la transacción el proceso 1
    public function lockForUpdate()
    {
        $producto = DB::transaction(function () {
            $producto = Product::query()->lockForUpdate()->find(1);
            sleep(10);
            $producto->stock += 1;
            $producto->stock_value += 1.1;
            $producto->save();
            return $producto;
        });
        return response()->json($producto);
    }


}
