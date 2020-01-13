<?php

use Carbon\Carbon;
use common\fixtures\helpers\FixturesFKHelper;

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'email'           => $faker->safeEmail,
    'password'        => Yii::$app->getSecurity()->generatePasswordHash('password_' . $index),
    'first_name'      => $faker->firstName,
    'last_name'       => $faker->lastName,
    'bio'             => $faker->boolean(30) ? $faker->sentences(5, true) : null,
    'date_of_birth'   => Carbon::parse($faker->dateTimeBetween('-60 years', '-18 years'))->toDateTimeString(),
    'phone'           => substr($faker->e164PhoneNumber, 1, 11),
    'skype'           => $faker->domainWord,
    'other_messenger' => $faker->boolean(70) ? $faker->domainWord : null,
    'location_id'     => FixturesFKHelper::getLocationID(),
    'profile_views'   => $faker->numberBetween(0, 1000),
    'last_active_at'  => Carbon::parse($faker->dateTimeBetween('-2 weeks', 'now'))->toDateTimeString(),
    'created_at'      => Carbon::parse($faker->dateTimeBetween('-6 months', '-1 day'))->toDateTimeString(),
    'updated_at'      => Carbon::now('UTC')->toDateTimeString(),
];
