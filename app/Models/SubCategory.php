<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    protected $table = 'sub_categories';

    protected $fillable = [
        'name', 'slug', 'category_id', 'created_by', 'status',
    ];

    public function Category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id')->select('id', 'name');
    }
}
