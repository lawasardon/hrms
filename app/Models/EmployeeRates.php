<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeRates extends Model
{
    use HasFactory;

    protected $table = 'employee_rates';

    protected $fillable = ['employee_id', 'id_number', 'monthly_rate', 'rate_perday'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function deduction()
    {
        return $this->hasMany(Deduction::class);
    }
}
