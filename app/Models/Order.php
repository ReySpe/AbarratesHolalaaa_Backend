<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $collection = 'Orders';

    protected $fillable = [ 'customer_id', 'date', 'email_address', 'iva', 'payment_method', 
    'transaction_id', 'subtotal', 'total', 'status', 'order_detail', 'shipping_data'];

}
