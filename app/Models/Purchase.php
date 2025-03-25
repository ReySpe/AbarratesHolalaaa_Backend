<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $collection = 'Purchases';
    
    protected $fillable = ['supplier_id','products', 'date','status', 'total'];

    protected $casts = ['date' => 'datetime','products' => 'array'];

}
