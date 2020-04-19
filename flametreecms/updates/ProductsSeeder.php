<?php namespace GodSpeed\FlametreeCMS\Updates;


use Seeder;
use GodSpeed\FlametreeCMS\Models\ProducerCategory;


class ProductsSeeder extends Seeder
{
    public function run()
    {
        if (env('APP_ENV') === 'acceptance') {
            return;
        }
       $productCategories = [
           "Fresh Fruit and Vegetable" => [
                [
                    "featured_image" => "products/fresh-fruit-and-vegetable/daptoCommunityFarm.jpg",
                    "name" => "Dapto Community Farm",
                    "origin" => "Dapto"
                ],
                   [
                       "featured_image" => "products/fresh-fruit-and-vegetable/greenConnect.jpg",
                       "name" => "Green Connect",
                       "origin" => "Warrawong"
                   ],
                   [
                       "featured_image" => "products/fresh-fruit-and-vegetable/glenbernieOrchard.jpg",
                       "name" => "Glenbernie Orchard",
                       "origin" => "Darkes Forest"
                   ],
                   [
                       "featured_image" => "products/fresh-fruit-and-vegetable/popesProduce.jpg",
                       "name" => "Popes Produce",
                       "origin" => "Woonona"
                   ],
                   [
                       "featured_image" => "products/fresh-fruit-and-vegetable/moonacresFarm.jpeg",
                       "name" => "Moonacres Farm",
                       "origin" => "Fitzroy Falls"
                   ],
           ],
           "Groceries" => [
               [
                   "featured_image" => "products/groceries/BalineseSpiceMagic.jpg",
                   "name" => "Balinese Spice Magic & Tempeh Temple",
                   "origin" => "Unknown"
               ],
               [
                   "featured_image" => "products/groceries/Makutchen.jpg",
                   "name" => "Ma Kutchen Organics",
                   "origin" => "Sutherland Shire"
               ],
               [
                   "featured_image" => "products/groceries/countryValley.jpg",
                   "name" => "Country Valley",
                   "origin" => "Picton NSW"
               ],
               [
                   "featured_image" => "products/groceries/RandallRice.jpg",
                   "name" => "Randall Organic Rice",
                   "origin" => "Griffith in Central NSW"
               ],
               [
                   "featured_image" => "products/groceries/handNhoe.jpg",
                   "name" => "Hand 'N' Hoe Organics",
                   "origin" => "Comboyne on the NSW Mid-North Coast"
               ],
               [
                   "featured_image" => "products/groceries/malfroysGold.jpg",
                   "name" => "Malfroys Gold",
                   "origin" => " Rockley, Central NSW"
               ],
               [
                   "featured_image" => "products/groceries/MurrayViewOrganics.jpg",
                   "name" => "Murray View Organics",
                   "origin" => "Koraleigh, south-west NSW"
               ],
               [
                   "featured_image" => "products/groceries/vanillaAustralia.jpg",
                   "name" => "Vanilla Australlia",
                   "origin" => "Port Douglas, North Qld
Vanilla essence"
               ],
               [
                   "featured_image" => "products/groceries/daintreeCompany.jpg",
                   "name" => "Daintree Tea Company",
                   "origin" => "Diwan, Far North Qld
Black tea"
               ],
               [
                   "featured_image" => "products/groceries/honestToGoodness.jpg",
                   "name" => "Honest to Goodness",
                   "origin" => "Alexandria"
               ],
               [
                   "featured_image" => "products/groceries/pacificOrganics.jpg",
                   "name" => "Pacific Organics",
                   "origin" => "Silverwater"
               ],
               [
                   "featured_image" => "products/groceries/globalByNature.jpg",
                   "name" => "Global By Nature",
                   "origin" => "Lane Cove"
               ],
           ],
           "Dairy and Fridge Products" => [
               [
                   "featured_image" => "products/dairy-and-fridge-products/countryValley.jpg",
                   "name" => "Country Valley",
                   "origin" => "Picton NSW"
               ],
               [
                   "featured_image" => "products/dairy-and-fridge-products/marrokfarm.jpg",
                   "name" => "Marrok Farm",
                   "origin" => "Elands, Mid North Coast NSW"
               ],
               [
                   "featured_image" => "products/dairy-and-fridge-products/BalineseSpiceMagic.jpg",
                   "name" => "Balinese Spice Magic",
                   "origin" => "Wollongong"
               ],
           ],
           "Healthy Treats" => [
               [
                   "featured_image" => "products/healthy-treats/carobKitchen.jpg",
                   "name" => "The Carob Kitchen",
                   "origin" => "Port Elliot (SA)"
               ],
           ]
        ];


       foreach($productCategories as $key => $item){
            $product_category = ProducerCategory::create(["name" => $key]);
            if(count($productCategories[$key])){
                foreach($productCategories[$key] as $product){
                    $product_category->producers()->create($product);
                }
            }
       }
    }
}
