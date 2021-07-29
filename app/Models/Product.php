<?php

namespace App\Models;

use App\Models\Unit;
use App\Models\User;
use App\Models\Brand;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $table ='products';

    protected $fillable = [
        'product_name', 'slug', 'category_id', 'sub_category_id', 'brand_id', 'product_code', 'big_unit_id', 'small_unit_id',
        'stock_limitation', 'specification', 'created_by', 'status',
    ];

    public function category(){
        return $this->belongsTo(Category::class, 'category_id','id')->select('id', 'name');
    }

    public function subCategory(){
        return $this->belongsTo(SubCategory::class, 'sub_category_id', 'id')->select('id','name');
    }

    public function brand(){
        return $this->belongsTo(Brand::class, 'brand_id', 'id')->select('id', 'name');
    }

    public function bigUnit(){
        return $this->belongsTo(Unit::class, 'big_unit_id', 'id')->select('id','name');
    }

    public function smallUnit(){
        return $this->belongsTo(Unit::class, 'small_unit_id', 'id')->select('id','name');
    }

    public function creator(){
        return $this->belongsTo(User::class, 'created_by', 'id')->select('id', 'name', 'email');
    }
}
