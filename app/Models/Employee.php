<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Employee extends Model
{
    use HasFactory,HasApiTokens;
    protected $fillable=[
        'id'  ,'email', 'phoneNumber','active','name', 	'salary','avatar'
    ];
}
