<?php namespace GodSpeed\Essentials\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use GodSpeed\Essentials\Traits\HasBackendPermissions;

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
        'index' => ['godspeed.essentials.browse_trainings'],
        'preview' => ['godspeed.essentials.browse_trainings'],
        'create' => ['godspeed.essentials.create_trainings'],
        'update' => ['godspeed.essentials.edit_trainings'],
        'create_onSave' => ['godspeed.essentials.create_trainings'],
        'update_onSave' => ['godspeed.essentials.edit_trainings'],
        'update_onDelete' => ['godspeed.essentials.delete_trainings']
    ];

    public function __construct()
    {
        parent::__construct();
        $this->checkPermissions();
        BackendMenu::setContext('GodSpeed.Essentials', 'essentials', 'trainings');
    }
}
