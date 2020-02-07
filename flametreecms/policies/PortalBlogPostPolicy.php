<?php


namespace GodSpeed\FlametreeCMS\Policies;

use Backend\Models\User;
use Backend\Models\UserGroup;
use October\Rain\Database\Builder;
use RainLab\User\Facades\Auth;

class PortalBlogPostPolicy extends PortalBlogCategoryPolicy
{
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
                    $query->whereIn('user_group', $publicGroups)->orWhereNull('user_group');
                 })->orWhereDoesntHave('categories');
            },
            'user' => function (Builder $builder) {
                $groups = $this->subjectModel->groups->pluck('id')->toArray();
                $publicGroups = UserGroup::where('code', 'registered')->orWhere('guest')->pluck('id')->toArray();
                 $builder->whereHas('categories', function (Builder $query) use ($groups, $publicGroups) {
                    $query->whereIn(
                        'user_group',
                        array_merge([null ], $groups, $publicGroups)
                    )->get();
                 })->orWhereDoesntHave('categories');
            }

        ];

        return $condition[$userType];
    }
}
