<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
  protected $fillable = [
    'code',
    'name',
    'contact_person',
    'email',
    'phone',
    'address',
    'tax_number',
    'payment_terms',
    'status',
    'notes'
  ];

  protected $casts = [
    'status' => 'boolean'
  ];

  public function items()
  {
    return $this->hasMany(Item::class);
  }

  public function purchaseOrders()
  {
    return $this->hasMany(PurchaseOrder::class);
  }

  public function contracts()
  {
    return $this->hasMany(SupplierContract::class);
  }

  public function scopeActive($query)
  {
    return $query->where('status', true);
  }

  public function getFullAddressAttribute()
  {
    return $this->address;
  }

  public function getContactInfoAttribute()
  {
    return [
      'person' => $this->contact_person,
      'email' => $this->email,
      'phone' => $this->phone
    ];
  }
}
