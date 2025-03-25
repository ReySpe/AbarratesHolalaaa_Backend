<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $collection = 'Payments';

    protected $fillable = ['payer_id', 'payment_id', 'payment_email', 'amount', 'currency', 'payment_status'];
}
