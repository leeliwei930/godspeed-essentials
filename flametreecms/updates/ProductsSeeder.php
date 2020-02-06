<?php namespace GodSpeed\FlametreeCMS\Updates;


use Seeder;
use GodSpeed\FlametreeCMS\Models\ProducerCategory;


class ProductsSeeder extends Seeder
{
    public function run()
    {
       $productCategories = [
           "Fresh Fruit and Vegetable" => [
                [
                    "name" => "Dapto Community Farm",
                    "origin" => "Dapto"
                ],
                   [
                       "name" => "Green Connect",
                       "origin" => "Warrawong"
                   ],
                   [
                       "name" => "Glenbernie Orchard",
                       "origin" => "Darkes Forest"
                   ],
                   [
                       "name" => "Popes Produce",
                       "origin" => "Woonona"
                   ],
                   [
                       "name" => "Moonacres Farm",
                       "origin" => "Fitzroy Falls"
                   ],
           ],
           "Groceries" => [
               [
                   "name" => "Balinese Spice Magic & Tempeh Temple",
                   "origin" => "Unknown"
               ],
               [
                   "name" => "Ma Kutchen Organics",
                   "origin" => "Sutherland Shire"
               ],
               [
                   "name" => "Country Valley",
                   "origin" => "Picton NSW"
               ],
               [
                   "name" => "Randall Organic Rice",
                   "origin" => "Griffith in Central NSW"
               ],
               [
                   "name" => "Hand 'N' Hoe Organics",
                   "origin" => "Comboyne on the NSW Mid-North Coast"
               ],
               [
                   "name" => "Malfroys Gold",
                   "origin" => " Rockley, Central NSW"
               ],
               [
                   "name" => "Murray View Organics",
                   "origin" => "Koraleigh, south-west NSW"
               ],
               [
                   "name" => "Vanilla Australlia",
                   "origin" => "Port Douglas, North Qld
Vanilla essence"
               ],
               [
                   "name" => "Daintree Tea Company",
                   "origin" => "Diwan, Far North Qld
Black tea"
               ],
               [
                   "name" => "Honest to Goodness",
                   "origin" => "Alexandria"
               ],
               [
                   "name" => "Pacific Organics",
                   "origin" => "Silverwater"
               ],
               [
                   "name" => "Global By Nature",
                   "origin" => "Lane Cove"
               ],
           ],
           "Dairy and Fridge Products" => [
               [
                   "name" => "Country Valley",
                   "origin" => "Picton NSW"
               ],
               [
                   "name" => "Marrok Farm",
                   "origin" => "Elands, Mid North Coast NSW"
               ],
               [
                   "name" => "Balinese Spice Magic",
                   "origin" => "Wollongong"
               ],
           ],
           "Healthy Treats" => [
               [
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
