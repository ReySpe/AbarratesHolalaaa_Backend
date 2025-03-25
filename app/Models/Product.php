<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $collection = 'Products';
    
    protected $fillable = ['name', 'category_id', 'unit_stock', 'unit_price', 'status', 'product_image'];
}
