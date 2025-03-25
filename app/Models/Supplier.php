<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $collection = 'Suppliers';

    protected $fillable = ['brand_name', 'seller_name', 'phone_number'];
}
