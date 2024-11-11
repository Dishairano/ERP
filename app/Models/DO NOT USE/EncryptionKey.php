<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EncryptionKey extends Model
{
  protected $fillable = [
    'key_identifier',
    'public_key',
    'rotated_at',
    'is_active'
  ];

  protected $casts = [
    'rotated_at' => 'datetime',
    'is_active' => 'boolean'
  ];

  public static function getActive()
  {
    return static::where('is_active', true)
      ->latest('created_at')
      ->first();
  }

  public function rotate()
  {
    $this->update([
      'is_active' => false,
      'rotated_at' => now()
    ]);

    return static::create([
      'key_identifier' => uniqid('key_'),
      'public_key' => static::generateNewKey(),
      'is_active' => true
    ]);
  }

  protected static function generateNewKey()
  {
    // Implementation would depend on your encryption requirements
    return sodium_crypto_box_keypair();
  }
}
