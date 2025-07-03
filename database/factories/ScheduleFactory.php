<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use App\Model\Schedule;

$factory->define(Schedule::class, function (Faker $faker) {

    $scheduled_date = $faker->dateTimeBetween('+1day', '+14day');
    return [
        'ats_store_id'    => 11112,
        'ats_consumer_id' => null,
        'start_date'      => $scheduled_date->format('Y-m-d H:00:00'),
        'end_date'        => $scheduled_date->format('Y-m-d H:30:00'),
        'interview_venue' => null,
        'is_filled'       => 0
    ];
});
