<?php

namespace App;

use Cron\CronExpression;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'description',
        'command',
        'expression',
        'dont_overlap',
        'run_in_maintenance',
        'notification_email'
        ];

    // last_run
    public function getLastRunAttribute() {
        return 'N/A';
    }

    public function getNextRunAttribute() {
        return CronExpression::factory($this->getCronExpression())->getNextRunDate('now', 0, false, 'Asia/Kolkata')->format('Y-m-d h:i A');        
    }

    public function getCronExpression(){
        return $this->expression ? : '* * * * *';

    }
}

