<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employee';

    protected $fillable = [
        'user_id',
        'department',
        'name',
        'email',
        'address',
        'phone',
        'gender',
        'birthday',
        'religion',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
