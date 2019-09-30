<?php

namespace GodSpeed\FlametreeCMS\Models;

use Backend\Models\ExportModel;
use RainLab\User\Models\User as RainLabUser;

class VolunteerExport extends ExportModel {
    public function exportData($columns, $sessionKey = null)
    {
        $volunteers = RainLabUser::all();
        $volunteers->each(function($volunteer) use ($columns) {
            $volunteer->addVisible($columns);
        });
        return $volunteers->toArray();
    }

}
