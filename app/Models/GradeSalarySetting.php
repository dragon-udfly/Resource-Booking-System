<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GradeSalarySetting extends Model
{
    protected $table = 'grade_salary_settings';

    protected $fillable = [
        'grade',
        'min_salary',
        'max_salary',
    ];
}
