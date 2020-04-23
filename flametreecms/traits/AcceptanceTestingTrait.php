<?php

namespace GodSpeed\FlametreeCMS\Traits;

use Illuminate\Support\Facades\Event;

trait AcceptanceTestingTrait
{

    public function injectBackendFormCypressAttributes()
    {


    }

    public function autoLoginUser()
    {
        \BackendAuth::login(\BackendAuth::findUserByLogin('admin'), true);
    }

    public function bootAcceptanceTesting( $autoLogin = true)
    {
        if (\Schema::hasTable('backend_users')) {
            if ($autoLogin) {
                $this->autoLoginUser();
            }
            $this->injectBackendFormCypressAttributes();
        }
    }

}
