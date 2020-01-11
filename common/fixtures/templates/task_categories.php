<?php

use Carbon\Carbon;

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

return [
    'name'       => $faker->jobTitle,
    'slug'       => $faker->unique()->randomElement([
        'translation',
        'clean',
        'cargo',
        'neo',
        'flat',
        'repair',
        'beauty',
        'photo',
    ]),
    'created_at' => Carbon::now('UTC')->toDateTimeString(),
    'updated_at' => Carbon::now('UTC')->toDateTimeString(),
];
