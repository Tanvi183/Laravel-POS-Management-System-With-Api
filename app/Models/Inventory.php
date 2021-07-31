<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventories';
    
    protected $fillable = [
        'product_id', 'available_big_unit_qty', 'available_small_unit_qty', 'big_unit_sales_price', 'small_unit_sales_price'
    ];
}
