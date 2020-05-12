<?php namespace GodSpeed\Essentials\Updates;

use GodSpeed\Essentials\Models\Faq;
use GodSpeed\Essentials\Models\FaqCategory;
use GodSpeed\Essentials\Models\Playlist;
use GodSpeed\Essentials\Models\SpecialOrder;
use GodSpeed\Essentials\Models\Video;
use October\Rain\Support\Collection;
use RainLab\Blog\Models\Post;
use Seeder;

class ImageSlider extends Seeder
{

    public function run()
    {
        $slider = [
            'label' => "Home",
            "slides" => [
                [
                    "image" => "/person-holding-three-orange-carrots-1268101.jpg",
                    "title" => "FRESH ORGANIC 100% AUSTRALIA GROWN FRUITS AND VEGETABLES",
                    "caption" => "",
                    "titleAnimation" => "slideInUp",
                    "captionAnimation" => "slideInDown",
                    "showPrimaryActionButton" => "1",
                    "showSecondaryActionButton" => "1",
                    "primaryActionButtonText" => "CHECKOUT OUR PRODUCERS",
                    "secondaryActionButtonText" => "ORDER NOW",
                    "primaryActionButtonLink" => "#",
                    "secondaryActionButtonLink" => "#",
                    "primaryActionButtonType" => "btn-primary",
                    "secondaryActionButtonType" => "btn-outline-primary",
                ],
                [
                    "image" => "/top-view-photography-of-sliced-vegetable-and-fruits-992822.jpg",
                    "title" => "FRESH DAIRY PRODUCTS AND ORGANIC FOOD ",
                    "caption" => "",
                    "titleAnimation" => "slideInUp",
                    "captionAnimation" => "slideInDown",
                    "showPrimaryActionButton" => "1",
                    "showSecondaryActionButton" => "0",
                    "primaryActionButtonText" => "LEARN MORE",
                    "secondaryActionButtonText" => "",
                    "primaryActionButtonLink" => "#",
                    "secondaryActionButtonLink" => "",
                    "primaryActionButtonType" => "btn-primary",
                    "secondaryActionButtonType" => "btn-primary",
                ],
                [
                    "image" => "/agriculture-backyard-blur-close-up-296230.jpg",
                    "title" => "NO CHEMICAL PESTICIDE CROPS",
                    "caption" => "SAFE AND HEALTHY FOR EVERYONE",
                    "titleAnimation" => "slideInUp",
                    "captionAnimation" => "slideInDown",
                    "showPrimaryActionButton" => "0",
                    "showSecondaryActionButton" => "0",
                    "primaryActionButtonText" => "",
                    "secondaryActionButtonText" => "",
                    "primaryActionButtonLink" => "",
                    "secondaryActionButtonLink" => "",
                    "primaryActionButtonType" => "btn-primary",
                    "secondaryActionButtonType" => "btn-primary",
                ],
            ],
            "prev_slide_animation" => "fadeIn",
            "next_slide_animation" => "fadeIn",
            "autoplay" => 1,
            "interval" => 4500,
            "show_navigation" => 1,
            "navigation_prev_icon" => "fas fa-chevron-left",
            "navigation_next_icon" => "fas fa-chevron-right",
            "responsive_size" => 1,
            "size_w" => 640,
            "size_h" => 320,
            "navigation_control_shape" => "circle",
            "autohide_navigation_control" => 1,
            "navigation_control_bg_color" => "#7f8c8d",
            "navigation_color" => "#ffffff",
            "show_indicator" => 1,
            "indicator_active_class" => "fas fa-circle",
            "indicator_inactive_class" => "far fa-circle",

        ];


        \GodSpeed\Essentials\Models\ImageSlider::create($slider);

    }
}
