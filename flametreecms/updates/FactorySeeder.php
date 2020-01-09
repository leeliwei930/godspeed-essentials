<?php namespace GodSpeed\FlametreeCMS\Updates;

use GodSpeed\FlametreeCMS\Models\Faq;
use GodSpeed\FlametreeCMS\Models\FaqCategory;
use October\Rain\Support\Collection;
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


    }
}
