<?php

namespace GodSpeed\FlametreeCMS\Database\Factories;

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Backend\Facades\BackendAuth;
use Backend\Models\User as BackendUser;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Faker\Provider\en_AU\Address;
use Faker\Provider\Internet;
use Faker\Provider\Lorem;
use GodSpeed\FlametreeCMS\Models\Faq;
use GodSpeed\FlametreeCMS\Models\FaqCategory;
use GodSpeed\FlametreeCMS\Models\Playlist;
use GodSpeed\FlametreeCMS\Models\Producer;
use GodSpeed\FlametreeCMS\Models\ProducerCategory;
use GodSpeed\FlametreeCMS\Models\SpecialOrder;
use GodSpeed\FlametreeCMS\Models\event;
use GodSpeed\FlametreeCMS\Models\Video;
use GodSpeed\FlametreeCMS\Utils\Providers\YoutubeVideoProvider;
use Illuminate\Support\Str;
use RainLab\Blog\Models\Post;

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


$factory->define(ProducerCategory::class, function (Faker $faker) {
    return [
        'name' => $faker->text(25)
    ];
});


$factory->define(SpecialOrder::class, function (Faker $faker) {
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

$factory->define(Video::class, function (Faker $faker) {

    $faker->addProvider(new YoutubeVideoProvider($faker));

    return [
        'embed_id' =>$faker->getVideoEmbedId(),
        'type' => $faker->getVideoSource()
    ];
});


$factory->define(Playlist::class, function (Faker $faker) {

    return [
        'name' => $faker->word
    ];
});


$factory->define(Faq::class, function (Faker $faker) {
    return [
        'question' => $faker->sentence(12) . "?",
        'answer' => $faker->sentence
    ];
});


$factory->define(FaqCategory::class, function (Faker $faker) {

    $name = $faker->unique()->text(5);
    return [
        'name' => $name,
        'slug' => Str::slug($name)
    ];
});

$factory->define(Post::class, function (Faker $faker) {
    $backendUser = BackendUser::first();
    // if there is a backend user, impersonate that user and create the posts
    if(!is_null($backendUser)){
        BackendAuth::impersonate(BackendUser::first());
    }

    $title = $faker->text(50);
    return [
        'title' => $title,
        'excerpt' => $faker->paragraph(3),
        'content' => $faker->paragraph(3),
        'slug' => Str::slug($title),
        'published_at' => now(),
        'published' => true
    ];
});


$factory->define(Event::class, function (Faker $faker) {
    $title = $faker->unique()->sentence(15);
    $description = $faker->paragraph(3);
    $now = now();
    $content = $faker->sentence(15);
    return [
        'title' => $title,
        'description' => $description,
        'slug' => Str::slug($title),
        'started_at' => $now->addHours(1)->toDateTimeString(),
        'ended_at' => $now->addDays(1)->toDateTimeString(),
        'content_html' => "<p>$content</p>"
    ];
});
