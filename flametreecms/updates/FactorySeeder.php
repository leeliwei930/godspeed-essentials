<?php namespace GodSpeed\FlametreeCMS\Updates;

use GodSpeed\FlametreeCMS\Models\Faq;
use GodSpeed\FlametreeCMS\Models\FaqCategory;
use GodSpeed\FlametreeCMS\Models\Playlist;
use GodSpeed\FlametreeCMS\Models\SpecialOrder;
use GodSpeed\FlametreeCMS\Models\Video;
use October\Rain\Support\Collection;
use RainLab\Blog\Models\Post;
use Seeder;

class FactorySeeder extends Seeder
{

    public function run()
    {

        // create faqs
        factory(\GodSpeed\FlametreeCMS\Models\FaqCategory::class, 3)->create()
            ->each(function ($faqCategory) {
                $faqCategory->faqs()->create(factory(Faq::class)->make()->toArray());
        });

        // create video and playlist
        factory(Playlist::class)->create(['name' => 'science'])->each(function($playlist){
            $videos = factory(Video::class, 3)->create()->pluck('id')->toArray();
            $playlist->videos()->attach($videos);
        });

        //create special orders
        factory(SpecialOrder::class,50)->create();

        factory(Post::class, 5)->create();
    }
}