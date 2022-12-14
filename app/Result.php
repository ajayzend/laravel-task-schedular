<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $table = 'task_results';
    protected $fillable = [
        'duration',
        'result'
    ];

    protected $dates = [
        'run_at'
    ];
}
