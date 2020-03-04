<?php namespace GodSpeed\FlametreeCMS\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use October\Rain\Database\Builder;
use RainLab\Blog\Components\Posts;
use RainLab\Blog\Models\Post as BlogPost;
use Auth;

class PrivateAnnouncements extends Posts
{

    public function componentDetails()
    {
        return [
            'name'        => 'Private Announcements',
            'description' => 'Only show the announcements based on current logged in users\'s groups.'
        ];
    }

    protected function listPosts()
    {
        $category = $this->category ? $this->category->id : null;

        /*
         * List all the posts, eager load their categories
         */
        $isPublished = !$this->checkEditor();
        $groups = Auth::check() ? Auth::user()->groups->pluck('id')->toArray() : [];

        $posts = BlogPost::whereHas('categories', function (Builder $query) use ($groups) {
            $query = $query->whereNotNull('user_group');
            if (count($groups) > 0) {
                $query->whereIn('user_group', $groups);
            }
        })->with('categories')->listFrontEnd([
            'page'             => $this->property('pageNumber'),
            'sort'             => $this->property('sortOrder'),
            'perPage'          => $this->property('postsPerPage'),
            'search'           => trim(input('search')),
            'category'         => $category,
            'published'        => $isPublished,
            'exceptPost'       => is_array($this->property('exceptPost'))
                ? $this->property('exceptPost')
                : preg_split('/,\s*/', $this->property('exceptPost'), -1, PREG_SPLIT_NO_EMPTY),
            'exceptCategories' => is_array($this->property('exceptCategories'))
                ? $this->property('exceptCategories')
                : preg_split('/,\s*/', $this->property('exceptCategories'), -1, PREG_SPLIT_NO_EMPTY),
        ]);

        /*
         * Add a "url" helper attribute for linking to each post and category
         */
        $posts->each(function ($post) {
            $post->setUrl($this->postPage, $this->controller);

            $post->categories->each(function ($category) {
                $category->setUrl($this->categoryPage, $this->controller);
            });
        });

        return $posts;
    }
}
