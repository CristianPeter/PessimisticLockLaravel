<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class PessimisticController extends Controller
{
    public function lockShared()
    {
        $producto = DB::transaction(function () {
            $producto = Product::query()->lockForUpdate()->find(1);
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
        // stock = 10
        // stock value = 21.14

        // First request
        // stock = 11
        // stock_value = 22.24

        // Second request
        // stock = 12
        // stock_value = 23.34
    }

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
