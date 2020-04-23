<?php namespace GodSpeed\FlametreeCMS\Updates;


use GodSpeed\FlametreeCMS\Models\Producer;
use Seeder;
use GodSpeed\FlametreeCMS\Models\ProducerCategory;


class ProductsSeeder extends Seeder
{
    public function run()
    {
        if (env('APP_ENV') === 'acceptance') {
            return;
        }
       $producerCategories = [
           "Fresh Fruit and Vegetable" => [
                [
                    "featured_image" => "products/fresh-fruit-and-vegetable/daptoCommunityFarm.jpg",
                    "name" => "Dapto Community Farm",
                    "origin" => "Dapto",
                    "slug" => 'dapto-community-farm'
                ],
                   [
                       "featured_image" => "products/fresh-fruit-and-vegetable/greenConnect.jpg",
                       "name" => "Green Connect",
                       "origin" => "Warrawong",
                       "slug" => 'green-connect'

                   ],
                   [
                       "featured_image" => "products/fresh-fruit-and-vegetable/glenbernieOrchard.jpg",
                       "name" => "Glenbernie Orchard",
                       "origin" => "Darkes Forest",
                      "slug" => 'glenbernie-orchard'

                   ],
                   [
                       "featured_image" => "products/fresh-fruit-and-vegetable/popesProduce.jpg",
                       "name" => "Popes Produce",
                       "origin" => "Woonona",
                       "slug" => 'popes-produce'

                   ],
                   [
                       "featured_image" => "products/fresh-fruit-and-vegetable/moonacresFarm.jpeg",
                       "name" => "Moonacres Farm",
                       "origin" => "Fitzroy Falls",
                       "slug" => 'moonacres-farm'

                   ],
           ],
           "Groceries" => [
               [
                   "featured_image" => "products/groceries/BalineseSpiceMagic.jpg",
                   "name" => "Balinese Spice Magic & Tempeh Temple",
                   "origin" => "Unknown",
                   "slug" => 'balinese-spice-magic-&-tempeh-temple'

               ],
               [
                   "featured_image" => "products/groceries/Makutchen.jpg",
                   "name" => "Ma Kutchen Organics",
                   "origin" => "Sutherland Shire",
                   "slug" => 'ma-kutchen-organics'
               ],
               [
                   "featured_image" => "products/groceries/countryValley.jpg",
                   "name" => "Country Valley",
                   "origin" => "Picton NSW",
                   'slug' => 'country-valley'
               ],
               [
                   "featured_image" => "products/groceries/RandallRice.jpg",
                   "name" => "Randall Organic Rice",
                   "origin" => "Griffith in Central NSW",
                   'slug' => 'randall-organic-rice'

               ],
               [
                   "featured_image" => "products/groceries/handNhoe.jpg",
                   "name" => "Hand 'N' Hoe Organics",
                   "origin" => "Comboyne on the NSW Mid-North Coast",
                   'slug' => "hand-'n'-hoe-organics"

               ],
               [
                   "featured_image" => "products/groceries/malfroysGold.jpg",
                   "name" => "Malfroys Gold",
                   "origin" => " Rockley, Central NSW",
                   'slug' => "malfroys-gold"

               ],
               [
                   "featured_image" => "products/groceries/MurrayViewOrganics.jpg",
                   "name" => "Murray View Organics",
                   "origin" => "Koraleigh, south-west NSW",
                   'slug' => "murray-view-organics"

               ],
               [
                   "featured_image" => "products/groceries/vanillaAustralia.jpg",
                   "name" => "Vanilla Australia",
                   "origin" => "Port Douglas, North Qld Vanilla essence",
                   'slug' => "vanilla-australia"

               ],
               [
                   "featured_image" => "products/groceries/daintreeCompany.jpg",
                   "name" => "Daintree Tea Company",
                   "origin" => "Diwan, Far North Qld Black tea",
                   'slug' => "daintree-tea-company"

               ],
               [
                   "featured_image" => "products/groceries/honestToGoodness.jpg",
                   "name" => "Honest to Goodness",
                   "origin" => "Alexandria",
                   'slug' => "honest-to-goodness"

               ],
               [
                   "featured_image" => "products/groceries/pacificOrganics.jpg",
                   "name" => "Pacific Organics",
                   "origin" => "Silverwater",
                   'slug' => "pacific-organics"

               ],
               [
                   "featured_image" => "products/groceries/globalByNature.jpg",
                   "name" => "Global By Nature",
                   "origin" => "Lane Cove",
                   'slug' => "global-by-nature"

               ],
           ],
           "Dairy and Fridge Products" => [
               [
                   "featured_image" => "products/dairy-and-fridge-products/countryValley.jpg",
                   "name" => "Country Valley",
                   "origin" => "Picton NSW",
                   'slug' => "country-valley"

               ],
               [
                   "featured_image" => "products/dairy-and-fridge-products/marrokfarm.jpg",
                   "name" => "Marrok Farm",
                   "origin" => "Elands, Mid North Coast NSW",
                   'slug' => "marrok-farm"

               ],
               [
                   "featured_image" => "products/dairy-and-fridge-products/BalineseSpiceMagic.jpg",
                   "name" => "Balinese Spice Magic",
                   "origin" => "Wollongong",
                   'slug' => "balinese-spice-magic"

               ],
           ],
           "Healthy Treats" => [
               [
                   "featured_image" => "products/healthy-treats/carobKitchen.jpg",
                   "name" => "The Carob Kitchen",
                   "origin" => "Port Elliot (SA)",
                   'slug' => "the-carob-kitchen"

               ],
           ]
        ];


       foreach($producerCategories as $key => $item){
            $producerCategory = ProducerCategory::create(["name" => $key]);
            if(count($producerCategories[$key])){
                foreach($producerCategories[$key] as $producer){
                    $producerRecord = Producer::firstOrCreate(['slug' => $producer['slug']] , $producer);
                    $producerCategory->producers()->attach($producerRecord);
                }
            }
       }
    }
}
