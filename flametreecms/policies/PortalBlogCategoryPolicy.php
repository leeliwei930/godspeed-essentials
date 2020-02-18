<?php


namespace GodSpeed\FlametreeCMS\Policies;

use October\Rain\Database\Builder;
use RainLab\User\Facades\Auth;
use RainLab\Blog\Models\Category;

class PortalBlogCategoryPolicy extends PolicyBase
{


    /**
     * This method will be place in plugin ModelClass::extend static function closure
     *
     * @param $resourceModel
     */

    public static function guard()
    {

        Category::extend(function ($model) {
            PortalBlogCategoryPolicy::check($model);
        });
    }
    public static function check($resourceModel)
    {
        $instance = self::make($resourceModel);

        // Enforce this policy when the env meet the condition
        if ($instance->appRunningEnv) {
            // restrict the blog categories based on the predefine rule
            $instance->conditionClosure = $instance->getRuleConditionClosure();
            $instance->resourceModel::addGlobalScope('id', $instance->conditionClosure);
        }

        // TODO: Implement verify() method.
    }

    /**
     * create an instance of this policy, and setup the instance attributes
     * @param $resourceModel
     * @return PortalBlogCategoryPolicy
     */
    public static function make($resourceModel)
    {
        // TODO: Implement make() method.
        $instance = new self();

        $instance->appRunningEnv = !\App::runningInBackend();
        $instance->subjectModel = Auth::check() ? Auth::user() : null;
        $instance->resourceModel = $resourceModel;
        return $instance;
    }

    /**
     * @return mixed
     * return a rule closure based on a user type
     */
    public function getRuleConditionClosure()
    {
        $userType = (is_null($this->subjectModel))? "guest" : "user";
        $condition = [
            'guest' => function (Builder $builder) {
                $builder->where('user_group', null);
            },
            'user' => function (Builder $builder) {
                $groups = $this->subjectModel->groups->pluck('id')->toArray();

                $builder->orWhereNull('user_group')->orWhereIn('user_group', array_merge([null], $groups));
            }

        ];

        return $condition[$userType];
    }
}
