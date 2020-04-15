<?php

namespace GodSpeed\FlametreeCMS\Models;

use Backend\Models\ExportModel;
use RainLab\User\Models\UserGroup;
use RainLab\User\Models\User as RainLabUser;

class VolunteerExport extends ExportModel
{
    public function exportData($columns, $sessionKey = null)
    {
        $volunteerGroup = UserGroup::whereCode('volunteer')->first();
        if (!is_null($volunteerGroup)) {
            return [];
        }
        $volunteers = $volunteerGroup->users()->get();


        $volunteers->each(function ($volunteer) use ($columns) {
            $volunteer->addVisible($columns);
        });
        return $volunteers->toArray();
    }
}
