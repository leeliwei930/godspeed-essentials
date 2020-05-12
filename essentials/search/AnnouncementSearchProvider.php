<?php
/**
 * Created by Li Wei Lee, on 26/4/2020.
 */
namespace GodSpeed\Essentials\Search;

use Cms\Classes\Page;

use GodSpeed\Essentials\Policies\PortalBlogPostsPolicy;

use OFFLINE\SiteSearch\Classes\Providers\ResultsProvider;
use RainLab\Blog\Models\Post;

class AnnouncementSearchProvider extends ResultsProvider
{
    public function search()
    {
        $query = $this->query;

        PortalBlogPostsPolicy::guard();

        $matching = Post::where('title', 'like', "%{$query}%")
            ->orWhere('excerpt', 'like', "%{$query}%")
            ->orWhere('slug', 'like', "%{$query}%")
            ->orWhere('content', 'like', "%{$query}%")
            ->get();

        foreach ($matching as $match) {

            $result = $this->newResult();

            $result->relevance = 1;
            $result->title = $match->title ;
            if($match->featured_images->count() > 0){
                $result->thumb = $match->featured_images->first();

            }
            $result->url = Page::url('blog/announcement', ['slug' => $match->slug]);
            $result->excerpt =  $match->content;
            $result->model = $match;
            $result->meta = [
            ];

            // Add the results to the results collection
            $this->addResult($result);
        }

        return $this;
    }

    public function displayName()
    {
        return "Announcement";
    }

    public function identifier()
    {
        return "GodSpeed.Essentials";
    }



}
