<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $table = 'payroll';

    protected $fillable =
        [
            'department_id',
            'id_number',
            'employee_id',
            'monthly_rate',
            'rate_perday',
            'duration',
            'total_working_days',
            'over_time',
            'salary',
            'status',
        ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function deduction()
    {
        return $this->hasMany(Deduction::class);
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
