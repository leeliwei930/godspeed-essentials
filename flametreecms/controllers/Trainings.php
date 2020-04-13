<?php namespace GodSpeed\FlametreeCMS\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use GodSpeed\FlametreeCMS\Traits\HasBackendPermissions;

/**
 * Trainings Back-end Controller
 */
class Trainings extends Controller
{
    use HasBackendPermissions;
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $actionPermissions = [
        'index' => ['godspeed.flametreecms.browse_trainings'],
        'preview' => ['godspeed.flametreecms.browse_trainings'],
        'create' => ['godspeed.flametreecms.create_trainings'],
        'update' => ['godspeed.flametreecms.edit_trainings'],
        'create_onSave' => ['godspeed.flametreecms.create_trainings'],
        'update_onSave' => ['godspeed.flametreecms.edit_trainings'],
        'update_onDelete' => ['godspeed.flametreecms.delete_trainings']
    ];

    public function __construct()
    {
        parent::__construct();
        $this->checkPermissions();
        BackendMenu::setContext('GodSpeed.FlametreeCMS', 'flametreecms', 'trainings');
    }
}
