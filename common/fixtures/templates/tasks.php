<?php

use Carbon\Carbon;
use common\fixtures\helpers\FixturesFKHelper;

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$helper = new FixturesFKHelper;
$createdAt = Carbon::parse($faker->dateTimeBetween('-1 months', 'now'));

return [
    'owner_id'    => $helper->getUserID(true),
    'status'      => $faker->randomElement(['new', 'cancelled', 'failed', 'in progress', 'completed', 'expired',]),
    'agent_id'    => $faker->boolean(50) ? $helper->getUserID() : null,
    'name'        => $faker->bs,
    'description' => $faker->sentences(6, true),
    'price'       => $faker->numberBetween(500, 9000),
    'expired_at'  => Carbon::parse($faker->dateTimeBetween($createdAt, '+1 months')),
    'category_id' => $faker->numberBetween(1, 8),
    'location_id' => $faker->boolean(80) ? $helper->getLocationID() : null,
    'created_at'  => $createdAt->toDateTimeString(),
    'updated_at'  => Carbon::now('UTC')->toDateTimeString(),
];
