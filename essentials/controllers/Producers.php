<?php namespace GodSpeed\Essentials\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use GodSpeed\Essentials\Traits\HasBackendPermissions;

/**
 * Products Back-end Controller
 */
class Producers extends Controller
{
    use HasBackendPermissions;

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController',
    ];
    public $actionPermissions = [
        'index' => ['godspeed.essentials.browse_producers'],
        'preview' => ['godspeed.essentials.browse_producers'],
        'create' => ['godspeed.essentials.create_producers'],
        'update' => ['godspeed.essentials.edit_producers'],
        'create_onSave' => ['godspeed.essentials.create_producers'],
        'update_onSave' => ['godspeed.essentials.edit_producers'],
        'update_onDelete' => ['godspeed.essentials.delete_producers']
    ];
    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {
        parent::__construct();
        $this->checkPermissions();

        BackendMenu::setContext('GodSpeed.Essentials', 'essentials', 'producers');
    }
}
