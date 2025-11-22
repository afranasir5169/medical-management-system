<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrnItem extends Model
{
    protected $fillable = [
        'grn_id','item_code','item_name','quantity','price_per_unit','total_price'
    ];

    public function grn()
    {
        return $this->belongsTo(Grn::class);
    }
}
