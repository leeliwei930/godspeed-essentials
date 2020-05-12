<?php

namespace GodSpeed\Essentials\Search;

use Cms\Classes\Page;
use GodSpeed\Essentials\Models\Producer;
use Intervention\Image\Facades\Image;
use OFFLINE\SiteSearch\Classes\Providers\ResultsProvider;
use System\Classes\MediaLibrary;
use System\Models\File;

class ProducerSearchProvider extends ResultsProvider
{
    public function search()
    {
        $query = $this->query;

        $matching = Producer::where('name', 'like', "%{$query}%")
            ->orWhere('origin', 'like', "%{$query}%")
            ->orWhere('slug', 'like', "%{$query}%")
            ->get();

        foreach ($matching as $match) {

            $result = $this->newResult();

            $result->relevance = 2;
            $result->title = $match->name . ", " . $match->origin;

            $result->url = Page::url('producer/products', ['producer' => $match->slug]);

            $result->model = $match;
            $result->meta = [
                'featured_image' =>  MediaLibrary::url($match->featured_image)
            ];

            // Add the results to the results collection
            $this->addResult($result);
        }

        return $this;
    }

    public function displayName()
    {
        return "Producer";
    }

    public function identifier()
    {
        return "GodSpeed.Essentials";
    }



}
