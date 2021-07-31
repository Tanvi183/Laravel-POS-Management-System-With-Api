<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $table='purchases';
    
    protected $fillable = [
        'purchase_date', 'challan_no', 'note', 'total_amount', 'supplier_id', 'created_by'
    ];
}
