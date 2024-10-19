<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    use HasFactory;

    protected $table = 'leave';

    protected $fillable = [
        'user_id',
        'department_id',
        'date_filed',
        'name',
        'date_start',
        'date_end',
        'total_days_leave',
        'type_of_day',
        'type_of_leave',
        'reason_to_leave',
        'reason_of_rejection',
        'status',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
