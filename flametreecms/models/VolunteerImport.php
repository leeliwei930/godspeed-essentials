<?php

namespace GodSpeed\FlametreeCMS\Models;

use Backend\Models\ImportModel;
use Backend\Models\UserGroup;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use RainLab\User\Facades\Auth;
use RainLab\User\Models\User;
use RainLab\User\Models\User as RainLabUser;

class VolunteerImport extends ImportModel {



    protected $rules =  [];
    public function importData($results, $sessionKey = null)
    {
        $volunteerGroup = UserGroup::whereCode('volunteers')->first();
        if(is_null($volunteerGroup)){
            $volunteerGroup = new UserGroup();
            $volunteerGroup->name = "Volunteers";
            $volunteerGroup->code = lcfirst($volunteerGroup->name);
            $volunteerGroup->save();
        }
        $volunteerGroup = UserGroup::whereCode('volunteers')->first();

        foreach ($results as $row => $data) {
            try {

                $volunteer = Auth::registerGuest($data);
                $volunteer->convertToRegistered();
                $volunteer->groups()->save($volunteerGroup);
                $this->logCreated();
            } catch (\Exception $ex) {
                $this->logError($row, $ex->getMessage());
            }
        }
    }
}
