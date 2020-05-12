<?php namespace GodSpeed\Essentials\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use GodSpeed\Essentials\Traits\HasBackendPermissions;

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
        'index' => ['godspeed.essentials.browse_events'],
        'preview' => ['godspeed.essentials.browse_events'],
        'create' => ['godspeed.essentials.create_events'],
        'update' => ['godspeed.essentials.edit_events'],
        'create_onSave' => ['godspeed.essentials.create_events'],
        'update_onSave' => ['godspeed.essentials.edit_events'],
        'update_onDelete' => ['godspeed.essentials.delete_events']
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {

        $this->checkPermissions();
        parent::__construct();

        BackendMenu::setContext('GodSpeed.Essentials', 'essentials', 'events');
    }
}
