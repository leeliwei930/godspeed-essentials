<?php


namespace GodSpeed\FlametreeCMS\Policies;

use Backend\Models\User;
use Backend\Models\UserGroup;
use October\Rain\Database\Builder;
use RainLab\Blog\Models\Category;
use RainLab\Blog\Models\Post;
use RainLab\User\Facades\Auth;

class PortalBlogPostPolicy extends PolicyBase
{
    public static function guard()
    {
        Post::extend(function ($model) {
            PortalBlogPostPolicy::check($model);
        });
    }
    public static function check($resourceModel)
    {

        $instance = self::make($resourceModel);

        // TODO: Implement check() method.
        if ($instance->appRunningEnv) {
            // restrict the blog categories based on the predefine rule
            $instance->conditionClosure = $instance->getRuleConditionClosure();
            $instance->resourceModel::addGlobalScope('id', $instance->conditionClosure);
        }
    }

    public static function make($resourceModel)
    {
        // TODO: Implement make() method.
        $instance = new self();

        $instance->appRunningEnv = !\App::runningInBackend();
        $instance->subjectModel = Auth::check() ? Auth::user() : null;
        $instance->resourceModel = $resourceModel;
        return $instance;
    }


    public function getRuleConditionClosure()
    {
        $userType = (is_null($this->subjectModel))? "guest" : "user";
        $condition = [
            'guest' => function (Builder $builder) {
                $publicGroups = UserGroup::where('code', 'guest')->pluck('id')->toArray();
                 $builder->whereHas('categories', function ($query) use ($publicGroups) {
                    $query->orWhereIn('user_group', $publicGroups)->orWhereNull('user_group');
                 })->orWhereDoesntHave('categories');
            },
            'user' => function (Builder $builder) {
                $groups = $this->subjectModel->groups->pluck('id')->toArray();
                $publicCategories = Category::where('user_group', null)->pluck('id')->toArray();
                 $builder->whereHas('categories', function (Builder $query) use ($groups, $publicCategories) {
                    $query->orWhereIn(
                        'user_group',
                        array_merge($groups, $publicCategories)
                    );
                 })->orWhereDoesntHave('categories');
            }

        ];

        return $condition[$userType];
    }
}
