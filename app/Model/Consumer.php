<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use app\User;

class Consumer extends Model
{
    public function consumerInfo()
    {
        return $this->hasMany(Consumer::class, 'ats_consumer_id', 'ats_consumer_id');
    }

    public function scheduleData()
    {
        return $this->hasMany(Schedule::class, 'ats_consumer_id', 'ats_consumer_id');
    }

    public function userData()
    {
        return $this->hasMany(User::class, 'id', 'user_id');
    }
}


