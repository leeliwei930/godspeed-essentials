<?php namespace GodSpeed\FlametreeCMS\Updates;

use GodSpeed\FlametreeCMS\Models\Faq;
use GodSpeed\FlametreeCMS\Models\FaqCategory;
use GodSpeed\FlametreeCMS\Models\Playlist;
use GodSpeed\FlametreeCMS\Models\Referral;
use GodSpeed\FlametreeCMS\Models\SpecialOrder;
use GodSpeed\FlametreeCMS\Models\Training;
use GodSpeed\FlametreeCMS\Models\Video;
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

        // create faqs
        factory(\GodSpeed\FlametreeCMS\Models\FaqCategory::class, 3)->create()
            ->each(function ($faqCategory) {
                $faqCategory->faqs()->create(factory(Faq::class)->make()->toArray());
            });

        // create video and playlist
        factory(Playlist::class)->create(['name' => 'science'])->each(function ($playlist) {
            $videos = factory(Video::class, 3)->create()->pluck('id')->toArray();
            $playlist->videos()->attach($videos);
        });

        //create special orders
        factory(SpecialOrder::class, 50)->create();

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
    }

    public function tearDown()
    {
        Post::where('slug', '!=', 'first-blog-post')->delete();
    }
}
