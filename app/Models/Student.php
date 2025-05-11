<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $connection = 'master_db';

    protected $table = 'students';

    protected $fillable = [
        'full_name',
        'email_student',
        'student_number',
        'entry_year',
        'major',
        'gender',
        'status',
    ];
}
