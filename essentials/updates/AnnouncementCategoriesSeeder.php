<?php namespace GodSpeed\Essentials\Updates;

use Cms\Classes\Theme;
use Illuminate\Support\Str;
use October\Rain\Support\Collection;
use RainLab\Blog\Models\Category;
use Seeder;

class AnnouncementCategoriesSeeder extends Seeder
{

    public function run()
    {
        $annoucementCategories = [
            [
                'name' => "Cooking",
                'featured_image' => 'announcement-categories/cooking.jpg'
            ],
            [
                'name' => "Discount",
                'featured_image' => 'announcement-categories/discount.jpg'
            ],
            [
                'name' => "Health",
                'featured_image' => 'announcement-categories/health.jpg'
            ],
            [
                'name' => "Organic",
                'featured_image' => 'announcement-categories/organic.jpg'

            ],
            [
                'name' => "Cultivation",
                'featured_image' => 'announcement-categories/cultivation.jpg'

            ],
            [
                'name' => "Agriculture",
                'featured_image' => 'announcement-categories/agriculture.jpg'

            ],
            [
                'name' => "Event",
                'featured_image' => 'announcement-categories/event.jpg'

            ],
            [
                'name' => "Meeting",
                'featured_image' => 'announcement-categories/meeting.jpg'

            ],
            [
                'name' => "Membership",
                'featured_image' => 'announcement-categories/membership.png'
            ]
        ];
        if(Theme::getActiveThemeCode() !== 'flametree-theme'){
            return;
        }
        // teardown
        collect($annoucementCategories)->each(function ($category) {
            $category = Category::where('name', $category['name'])->first();
            if (!is_null($category)) {
                $category->delete();
            }
        });

        collect($annoucementCategories)->each(function ($category) {
            Category::firstOrCreate($category);
        });
    }
}
