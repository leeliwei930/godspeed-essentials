<?php namespace GodSpeed\Essentials\Components;

use Cms\Classes\ComponentBase;
use RainLab\Blog\Components\Post;
use RainLab\Blog\Components\Posts;

class TrendingAnnouncement extends Posts
{
    public function componentDetails()
    {
        return [
            'name'        => 'Trending Announcement',
            'description' => 'Display a list of announcement in a card looking'
        ];
    }

    public function defineProperties()
    {
        return parent::defineProperties();
    }
}
