<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tymon\JWTAuth\Contracts\JWTSubject;
use MongoDB\Laravel\Eloquent\Model;

class Employee extends Model implements AuthenticatableContract, JWTSubject
{
    use HasFactory, Authenticatable;

    protected $collection = 'Employees';

    protected $fillable = [
        'first_name', 'last_name', 'phone', 'birth_date', 'hire_date', 'email', 'employee_type', 
        'gender', 'notes', 'address', 'password', 'status'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

}
