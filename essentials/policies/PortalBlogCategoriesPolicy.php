<?php


namespace GodSpeed\Essentials\Policies;

use October\Rain\Database\Builder;
use RainLab\User\Facades\Auth;
use RainLab\Blog\Models\Category;

class PortalBlogCategoriesPolicy extends PolicyBase
{


    /**
     * This method will be place in plugin ModelClass::extend static function closure
     *
     * @param $resourceModel
     */

    public static function guard()
    {

        Category::extend(function ($model) {
            PortalBlogCategoriesPolicy::check($model);
        });
    }
    public static function check($resourceModel)
    {
        $instance = self::make($resourceModel);

        // Enforce this policy when the env meet the condition, when it is running on frontend
        if ($instance->appRunningEnv) {
            // restrict the blog categories based on the predefine rule
            $instance->conditionClosure = $instance->getRuleConditionClosure();
            // provide the scope that filter the data can be seen by the user with the closure passed in
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
        // check against the current user type, guest which mean there is no user logged in
        $userType = (is_null($this->subjectModel))? "guest" : "user";
        $condition = [
            'guest' => function (Builder $builder) {
                $builder->where('godspeed_essentials_user_group', null);
            },
            'user' => function (Builder $builder) {
                $groups = $this->subjectModel->groups->pluck('id')->toArray();
                /** return the category that is no user_group attach (public) +
                 * include the category that match any current logged in user_group
                **/
                $builder->orWhereNull('godspeed_essentials_user_group')->orWhereIn('godspeed_essentials_user_group', array_merge([null], $groups));
            }

        ];
        // return a specific closure that based on current logged in user
        return $condition[$userType];
    }
}
