<?php



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
use GodSpeed\FlametreeCMS\Models\Referral;
use GodSpeed\FlametreeCMS\Models\SpecialOrder;
use GodSpeed\FlametreeCMS\Models\Event;
use GodSpeed\FlametreeCMS\Models\Training;
use GodSpeed\FlametreeCMS\Models\Video;
use GodSpeed\FlametreeCMS\Utils\Providers\YoutubeVideoProvider;
use Illuminate\Support\Str;
use GodSpeed\FlametreeCMS\Models\Product;
use GodSpeed\FlametreeCMS\Models\ProductCategory;

use RainLab\Blog\Models\Post;
use RainLab\User\Models\UserGroup;

$factory->define(Producer::class, function (Faker $faker) {
    $faker->addProvider(new Address($faker));
    $faker->addProvider(new Lorem($faker));
    $faker->addProvider(new Internet($faker));
    $name = $faker->word;
    return [
        "name" => $name,
        "slug" => Str::slug($name),
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
        'email' => $faker->unique()->email,
        'phone_number' => $faker->phoneNumber,
        'message' => $faker->paragraph
    ];
});

$factory->define(Video::class, function (Faker $faker) {

    $faker->addProvider(new YoutubeVideoProvider($faker));

    return [
        'embed_id' => $faker->getVideoEmbedId(),
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
    if (!is_null($backendUser)) {
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
    $now = Carbon::parse($faker->dateTimeThisDecade()->format("Y-m-d H:i"));
    $content = $faker->sentence(15);
    return [
        'title' => $title,
        'description' => $description,
        'slug' => Str::slug($title),
        'started_at' => $now->toDateTimeString(),
        'ended_at' => $now->addHours(1)->toDateTimeString(),
        'content_html' => "<p>$content</p>"
    ];
});

$factory->define(Training::class, function (Faker $faker) {
    $title = $faker->words(10, true);
    return [
        'title' => $title,
        'slug' => Str::slug($title),
        'content_html' => $faker->sentences(10, true),
        'video_playlist_id' => Playlist::all()->random()->id
    ];
});

$factory->define(Referral::class, function (Faker $faker) {
    $code = bin2hex(openssl_random_pseudo_bytes(10));
    $validBeforeDate = now()->addDays(25);
    $validAfterDate = now();
    $code = Str::upper($code);
    $role = UserGroup::with('users')->get()->random();

    return [
        'code' => $code,
        'valid_before' => $validBeforeDate->toDateTimeString(),
        'valid_after' => $validAfterDate->toDateTimeString(),
        'capped' => true,
        'usage_left' => $faker->numberBetween(30, 80),
        'timezone' => "Australia/Sydney",
        'user_group_id' => $role->id
    ];
});

$factory->define(ProductCategory::class, function (Faker $faker) {
    $name = $faker->unique()->text(10);
    $description = $faker->unique()->sentences(5, true);
    return [
        'name' => $name,
        'slug' => Str::slug($name),
        'description' => "<p>$description</p>"
    ];
});

$factory->define(Product::class, function (Faker $faker) {
    $faker->addProvider(new \Bezhanov\Faker\Provider\Food($faker));
    $productName = $faker->unique()->ingredient;
    $featureTitles = $faker->unique()->words(10);
    $featureDescription = $faker->unique()->words(10);
    $features = collect([]);

    collect($featureTitles)->each(function ($feature, $index) use ($features, $featureDescription) {
        $features->push([
            'title' => $feature,
            'description' => $featureDescription[$index]
        ]);
    });

    $isProduct = random_int(0, 1);
    $hasStockLimit = random_int(0, 1);
    $stockLeft = 0;

    if ($hasStockLimit) {
        $stockLeft = random_int(1, 10);
    }

    if ($isProduct) {
        return [
            'name' => $productName,
            'type' => 'product',
            'price' => random_int(5, 12),
            'has_stock_limit' => $hasStockLimit,
            'stock_left' => $stockLeft,
            'is_active' => random_int(0, 1),
            'slug' => Str::slug($productName),
            'currency' => $faker->currencyCode,
            'features' => $features->toArray(),
        ];
    } else {
        $billingCycle = array_random(['daily', 'weekly', 'monthly', 'annually'], 1)[0];
        return [
            'name' => $productName,
            'type' => 'service',
            'price' => random_int(5, 12),
            'has_stock_limit' => $hasStockLimit,
            'stock_left' => $stockLeft,
            'is_active' => random_int(0, 1),
            'slug' => Str::slug($productName),
            'currency' => $faker->currencyCode,
            'billing_cycle' => $billingCycle,
            'features' => $features->toArray(),
        ];
    }
});
