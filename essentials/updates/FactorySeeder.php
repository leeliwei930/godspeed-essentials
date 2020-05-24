<?php namespace GodSpeed\Essentials\Updates;

use Cms\Classes\Theme;
use GodSpeed\Essentials\Models\Faq;
use GodSpeed\Essentials\Models\FaqCategory;
use GodSpeed\Essentials\Models\Playlist;
use GodSpeed\Essentials\Models\Producer;
use GodSpeed\Essentials\Models\Product;
use GodSpeed\Essentials\Models\ProductCategory;
use GodSpeed\Essentials\Models\Referral;
use GodSpeed\Essentials\Models\Training;
use GodSpeed\Essentials\Models\Video;
use October\Rain\Support\Collection;
use RainLab\Blog\Models\Category;
use RainLab\Blog\Models\Post;
use RainLab\User\Models\UserGroup;
use Seeder;

class FactorySeeder extends Seeder
{

    public function run()
    {
        if (env('APP_ENV') === 'acceptance') {
            return;
        }
        if(Theme::getActiveThemeCode() !== 'godspeed-flametree-theme'){
            return;
        }
        // create faqs
        factory(\GodSpeed\Essentials\Models\FaqCategory::class, 20)->create()
            ->each(function ($faqCategory) {
                $faqs = factory(Faq::class, 15)->create();
                $faqCategory->faqs()->attach($faqs->pluck('id'));
            });

        // create video and playlist
        factory(Playlist::class)->create(['name' => 'FLAMETREE PLAYLIST'])->each(function ($playlist) {
            $videos = factory(Video::class, 3)->create()->pluck('id')->toArray();
            $playlist->videos()->attach($videos);
        });

        //create special orders


        $this->tearDown();

        factory(Post::class, 120)->create()->each(function ($post) {
            $categories = Category::all()->random(3);
            $post->categories()->attach($categories);
        });

        factory(Training::class, 50)->create()->each(function ($training) {
            $roles = UserGroup::all()->random(3)->pluck('id');
            $training->user_group()->attach($roles);
        });

        factory(Referral::class, 80)->create()->each(function ($referral) {

        });

        factory(Product::class, 20)->create(['currency' =>  'AUD', 'type' => 'product'])->each(function ($product) {
            $producerCategoriesID = factory(ProductCategory::class, 5)->create()->pluck('id');
            $producer = Producer::all()->random();
            $product->categories()->attach($producerCategoriesID);
            $product->producer_id = $producer->id;
            $product->save();
        });
    }

    public function tearDown()
    {
        Post::where('slug', '!=', 'first-blog-post')->delete();
    }
}
