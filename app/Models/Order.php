<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'phone',
        'city',
        'district',
        'street',
        'building_no',
        'apartment_no',
        'before_discount',
        'after_discount',
        'status'
    ];
    public function order_details(): HasMany
    {
        return $this->hasMany(OrderDetails::class);
    }
}
