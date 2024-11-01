<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance';

    protected $fillable = [
        'id_number',
        'name',
        'department',
        'date',
        'time_in',
        'time_out',
        'status',
    ];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class, 'id_number', 'id_number');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'id_number');
    }

}
