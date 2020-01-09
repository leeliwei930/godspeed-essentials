<?php

namespace GodSpeed\FlametreeCMS\Database\Factories;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use Faker\Generator as Faker;
use Faker\Provider\en_AU\Address;
use Faker\Provider\Internet;
use Faker\Provider\Lorem;
use GodSpeed\FlametreeCMS\Models\Producer;
use GodSpeed\FlametreeCMS\Models\ProducerCategory;
use GodSpeed\FlametreeCMS\Models\SpecialOrder;
use GodSpeed\FlametreeCMS\Models\Video;
use GodSpeed\FlametreeCMS\Utils\Providers\YoutubeVideoProvider;
use Illuminate\Support\Str;


$factory->define(Producer::class, function (Faker $faker) {
    $faker->addProvider(new Address($faker));
    $faker->addProvider(new Lorem($faker));
    $faker->addProvider(new Internet($faker));
    return [
        "name" => $faker->word,
        "origin" => $faker->state,
        "website" => $faker->url
    ];
});


$factory->define(ProducerCategory::class, function(Faker $faker){
    return [
        'name' => $faker->text(25)
    ];
});


$factory->define(SpecialOrder::class, function(Faker $faker){
    $faker->addProvider(new Address($faker));
    $faker->addProvider(new Lorem($faker));
    $faker->addProvider(new Internet($faker));
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'phone_number' => $faker->phoneNumber,
        'message' => $faker->paragraph
    ];
});

$factory->define(Video::class, function(Faker $faker){

    $faker->addProvider(new YoutubeVideoProvider($faker));

    return [
        'embed_id' =>$faker->getVideoEmbedId(),
        'type' => $faker->getVideoSource()
    ];
});


