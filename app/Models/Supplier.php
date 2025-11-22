<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = [
        'supplier_id',
        'name',
        'company_name',
        'email',
        'phone',
        'address',
        'city',
        'district',
        'supply_type',
        'description',
    ];
}

