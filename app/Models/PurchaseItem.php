<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $table = 'purchase_items';

    protected $fillable = [
        'purchase_id', 'product_id', 'big_unit_price', 'small_unit_price', 'big_unit_qty', 'small_unit_qty'
    ];
}
