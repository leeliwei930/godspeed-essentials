<?php namespace GodSpeed\FlametreeCMS\Components;

use Cms\Classes\ComponentBase;
use GodSpeed\FlametreeCMS\Models\Settings;

class BusinessContact extends ComponentBase
{

    public $email;
    public $phone_number;
    public $address;
    public $po_box_address;
    public $facebook_link;
    public $twitter_link;
    public $instagram_link;


    public function componentDetails()
    {
        return [
            'name'        => 'BusinessContact',
            'description' => 'Include business contact data to CMS Page'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        $this->loadBusinessContact();
    }

    public function loadBusinessContact()
    {

        $settingInstance = Settings::instance();
        $this->email = $settingInstance->business_email;
        $this->phone_number = $settingInstance->business_phone_number;
        $this->address = $settingInstance->business_address;
        $this->po_box_address = $settingInstance->business_po_box_address;
        $this->facebook_link = $settingInstance->facebook_link;
        $this->twitter_link = $settingInstance->twitter_link;
        $this->instagram_link = $settingInstance->instagram_link;
    }
}
