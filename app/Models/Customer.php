<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $collection = "Customers";

    protected $fillable = [
        'first_name', 'last_name', 'phone_number', 'email', 'address',
        'notes', 'profile_image', 'status', 'password'
    ];
}
