<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
    protected $fillable = ['customer_id', 'order_date', 'total_amount', 'status'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}