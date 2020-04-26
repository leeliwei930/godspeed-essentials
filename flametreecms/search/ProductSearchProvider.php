<?php


namespace GodSpeed\FlametreeCMS\Search;

use Cms\Classes\Page;
use GodSpeed\FlametreeCMS\Models\Producer;
use GodSpeed\FlametreeCMS\Models\Product;
use OFFLINE\SiteSearch\Classes\Providers\ResultsProvider;
use System\Classes\MediaLibrary;

class ProductSearchProvider extends ResultsProvider
{
    public function search()
    {
        $query = $this->query;

        $matching = Product::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->orWhere('slug', 'like', "%{$query}%")
            ->isActive()
            ->get();

        foreach ($matching as $match) {
            $result = $this->newResult();

            $result->relevance = 3;
            $result->title = $match->name;

            $result->url = Page::url('producer/product', ['slug' => $match->slug]);

            $result->model = $match;
            $result->meta = [
                'featured_image' => !is_null($match->featured_image) ? MediaLibrary::url($match->featured_image) : null
            ];

            // Add the results to the results collection
            $this->addResult($result);
        }

        return $this;
    }

    public function displayName()
    {
        return "Product";
    }

    public function identifier()
    {
        return "GodSpeed.FlametreeCMS";
    }
}
