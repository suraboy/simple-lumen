<?php
$factory->define(App\Models\Ticket::class, function (Faker\Generator $faker) {
    return [
        'name' => [
            'en' => $faker->name,
            'th' => $faker->name,
        ],
        'description' => $faker->name,
        'tel' => '0987654321',
        'status' => 'accepted'
    ];
});
