<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingDevelopment extends Model
{
    use HasFactory;

    protected $fillable = ['training_name', 'trainer', 'start_date', 'end_date'];
}