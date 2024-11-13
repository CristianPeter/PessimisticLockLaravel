<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

/**
 * @mixin Builder
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @property string $id
 * @property integer $stock
 * @property float $stock_value
 */
class Product extends Model
{
    protected $table = 'table_products';

    use HasFactory;

}
