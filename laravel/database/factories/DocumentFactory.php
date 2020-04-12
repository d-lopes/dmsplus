<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Document;
use Faker\Generator as Faker;

$factory->define(Document::class, function (Faker $faker) {
    return [
        
        'filename' => $faker->file,
        'content' => $faker->text,
        'path' => $faker->url
    ];
});
