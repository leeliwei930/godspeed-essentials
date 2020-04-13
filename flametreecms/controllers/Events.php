<?php namespace GodSpeed\FlametreeCMS\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use GodSpeed\FlametreeCMS\Traits\HasBackendPermissions;

/**
 * Meetings Back-end Controller
 */
class Events extends Controller
{
    use HasBackendPermissions;
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];
    public $actionPermissions = [
        'index' => ['godspeed.flametreecms.browse_events'],
        'preview' => ['godspeed.flametreecms.browse_events'],
        'create' => ['godspeed.flametreecms.create_events'],
        'update' => ['godspeed.flametreecms.edit_events'],
        'create_onSave' => ['godspeed.flametreecms.create_events'],
        'update_onSave' => ['godspeed.flametreecms.edit_events'],
        'update_onDelete' => ['godspeed.flametreecms.delete_events']
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {

        $this->checkPermissions();
        parent::__construct();

        BackendMenu::setContext('GodSpeed.FlametreeCMS', 'flametreecms', 'events');
    }
}
