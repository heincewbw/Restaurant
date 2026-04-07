<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['subtotal', 'tax', 'total', 'status', 'paid_at', 'table_code'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
