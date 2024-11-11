<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = ['type', 'total_income', 'total_expense', 'net_income', 'assets', 'liabilities', 'equity', 'start_date', 'end_date'];

    // Automatically calculate net income if total income and total expense are provided
    public function getNetIncomeAttribute()
    {
        return $this->total_income - $this->total_expense;
    }

    // Automatically calculate equity as assets minus liabilities
    public function getEquityAttribute()
    {
        return $this->assets - $this->liabilities;
    }
}