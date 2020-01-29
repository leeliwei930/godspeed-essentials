<?php namespace GodSpeed\FlameTreeCMS\Components;

use Backend\Models\UserGroup;
use Cms\Classes\ComponentBase;

class TeamMember extends ComponentBase
{
    public $teamMembers = [];

    public function componentDetails()
    {
        return [
            'name'        => 'Team Member',
            'description' => 'Render a collection of team members'
        ];
    }

    public function defineProperties()
    {
        return [
            'groupName' => [
                'title' => "Group",
                'description' => "Select a group that will be displayed as team members",
                'type' => "dropdown",
            ]
        ];
    }

    public function onRun()
    {
        $this->loadTeamMembers();
    }

    public function loadTeamMembers()
    {
        $groupID = $this->property('groupName');
        $teamMembers = UserGroup::with(['users' , 'users.role'])->find($groupID);

        if (!is_null($teamMembers)) {
            $this->teamMembers = $teamMembers['users'];
        }
    }

    public function getGroupNameOptions()
    {
        return UserGroup::all()->pluck('name', 'id')->toArray();
    }
}
