<?php namespace GodSpeed\FlametreeCMS\Components;

use Cms\Classes\ComponentBase;
use RainLab\Blog\Components\Post;
use RainLab\Blog\Components\Posts;

class TrendingAnnouncement extends Posts
{
    public function componentDetails()
    {
        return [
            'name'        => 'TrendingAnnouncement - Basic ',
            'description' => 'Display a list of announcement in a card looking'
        ];
    }

    public function defineProperties()
    {
        return parent::defineProperties();
    }
}
