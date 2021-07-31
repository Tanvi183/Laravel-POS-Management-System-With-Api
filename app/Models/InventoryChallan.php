<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryChallan extends Model
{
    use HasFactory;

    protected $table='inventory_challans';

    protected $guarded=[];

    // protected $fillable = [
    //     'inventory_id', 'purchase_id', 'product_id', 'big_unit_sales_price', 'small_unit_sales_price', 'big_unit_cost_price', 
    //     'small_unit_cost_price', 'big_unit_qty', 'small_unit_qty', 'available_big_unit_qty', 'available_small_unit_qty'
    // ];
}
