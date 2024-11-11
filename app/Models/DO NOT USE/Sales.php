<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    // Define fillable fields
    protected $fillable = ['amount', 'user_id', 'sale_date'];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}