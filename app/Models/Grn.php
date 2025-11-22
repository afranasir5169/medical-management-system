<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grn extends Model
{
    protected $fillable = [
        'grn_number','invoice_number','supplier_id','grn_date',
        'total','discount_percent','net_total','remarks'
    ];

    protected $dates = ['grn_date'];

    public function items()
    {
        return $this->hasMany(GrnItem::class);
    }

    public function supplier()
    {
        return $this->belongsTo(\App\Models\Supplier::class, 'supplier_id');
    }
}

