<?php

namespace GodSpeed\Essentials\Models;

use Backend\Models\ImportModel;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use RainLab\User\Facades\Auth;
use RainLab\User\Models\User;
use RainLab\User\Models\User as RainLabUser;
use RainLab\User\Models\UserGroup;
use Event;

class VolunteerImport extends ImportModel
{


    protected $rules = [];


    public function importData($results, $sessionKey = null)
    {
        $volunteerGroup = UserGroup::whereCode('volunteer')->first();
        if (is_null($volunteerGroup)) {
            $volunteerGroup = new UserGroup();
            $volunteerGroup->name = "Volunteer";
            $volunteerGroup->code = lcfirst($volunteerGroup->name);
            $volunteerGroup->save();
        }

        $volunteerGroup = UserGroup::where('code', 'volunteer')->first();
        $guest = UserGroup::where('code' , 'guest')->first();
        foreach ($results as $row => $data) {
            try {
                $volunteer = Auth::registerGuest($data);

                $volunteer->convertToRegistered();


                $volunteer->groups()->add($volunteerGroup);
                $volunteer->groups()->remove($guest);
                $volunteer->is_activated = true;
                $volunteer->activated_at = now();
                $volunteer->forceSave();



                $this->logCreated();
            } catch (\Exception $ex) {
                $this->logError($row, $ex->getMessage());
            }
        }
    }
}
