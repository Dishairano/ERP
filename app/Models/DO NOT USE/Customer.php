<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'address'];

    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }
}