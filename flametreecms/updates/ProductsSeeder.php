<?php namespace GodSpeed\FlametreeCMS\Updates;


use Seeder;
use GodSpeed\FlametreeCMS\Models\ProductCategory as ProductCategory;


class ProductsSeeder extends Seeder
{
    public function run()
    {
       $productCategories = [
           "Fresh Fruit and Vegetable" => [
                [
                    "producer_name" => "Dapto Community Farm",
                    "producer_origin" => "Dapto"
                ],
                   [
                       "producer_name" => "Green Connect",
                       "producer_origin" => "Warrawong"
                   ],
                   [
                       "producer_name" => "Glenbernie Orchard",
                       "producer_origin" => "Darkes Forest"
                   ],
                   [
                       "producer_name" => "Popes Produce",
                       "producer_origin" => "Woonona"
                   ],
                   [
                       "producer_name" => "Moonacres Farm",
                       "producer_origin" => "Fitzroy Falls"
                   ],
           ],
           "Groceries" => [
               [
                   "producer_name" => "Balinese Spice Magic & Tempeh Temple",
                   "producer_origin" => "Unknown"
               ],
               [
                   "producer_name" => "Ma Kutchen Organics",
                   "producer_origin" => "Sutherland Shire"
               ],
               [
                   "producer_name" => "Country Valley",
                   "producer_origin" => "Picton NSW"
               ],
               [
                   "producer_name" => "Randall Organic Rice",
                   "producer_origin" => "Griffith in Central NSW"
               ],
               [
                   "producer_name" => "Hand 'N' Hoe Organics",
                   "producer_origin" => "Comboyne on the NSW Mid-North Coast"
               ],
               [
                   "producer_name" => "Malfroys Gold",
                   "producer_origin" => " Rockley, Central NSW"
               ],
               [
                   "producer_name" => "Murray View Organics",
                   "producer_origin" => "Koraleigh, south-west NSW"
               ],
               [
                   "producer_name" => "Vanilla Australlia",
                   "producer_origin" => "Port Douglas, North Qld
Vanilla essence"
               ],
               [
                   "producer_name" => "Daintree Tea Company",
                   "producer_origin" => "Diwan, Far North Qld
Black tea"
               ],
               [
                   "producer_name" => "Honest to Goodness",
                   "producer_origin" => "Alexandria"
               ],
               [
                   "producer_name" => "Pacific Organics",
                   "producer_origin" => "Silverwater"
               ],
               [
                   "producer_name" => "Global By Nature",
                   "producer_origin" => "Lane Cove"
               ],
           ],
           "Dairy and Fridge Products" => [
               [
                   "producer_name" => "Country Valley",
                   "producer_origin" => "Picton NSW"
               ],
               [
                   "producer_name" => "Marrok Farm",
                   "producer_origin" => "Elands, Mid North Coast NSW"
               ],
               [
                   "producer_name" => "Balinese Spice Magic",
                   "producer_origin" => "Wollongong"
               ],
           ],
           "Healthy Treats" => [
               [
                   "producer_name" => "The Carob Kitchen",
                   "producer_origin" => "Port Elliot (SA)"
               ],
           ]
        ];

       foreach($productCategories as $key => $item){
            $product_category = ProductCategory::create(["name" => $key]);
            if(count($productCategories[$key])){
                foreach($productCategories[$key] as $product){
                    $product_category->products()->create($product);
                }
            }
       }
    }
}
