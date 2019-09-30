<?php

namespace GodSpeed\FlametreeCMS\Models;

use Backend\Models\ImportModel;
use Illuminate\Support\Facades\Hash;
use RainLab\User\Models\User as RainLabUser;

class VolunteerImport extends ImportModel {



    protected $rules =  [];
    public function importData($results, $sessionKey = null)
    {
        foreach ($results as $row => $data) {
            try {
                $volunteer = new RainLabUser;
                $volunteer->fill($data);
                $volunteer->password = "secret123";
                $volunteer->password_confirmation = "secret123";
                $volunteer->save();
                $this->logCreated();
            } catch (\Exception $ex) {
                $this->logError($row, $ex->getMessage());
            }
        }
    }
}
