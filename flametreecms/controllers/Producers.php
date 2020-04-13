<?php namespace GodSpeed\FlametreeCMS\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use GodSpeed\FlametreeCMS\Traits\HasBackendPermissions;

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
        'index' => ['godspeed.flametreecms.browse_producers'],
        'preview' => ['godspeed.flametreecms.browse_producers'],
        'create' => ['godspeed.flametreecms.create_producers'],
        'update' => ['godspeed.flametreecms.edit_producers'],
        'create_onSave' => ['godspeed.flametreecms.create_producers'],
        'update_onSave' => ['godspeed.flametreecms.edit_producers'],
        'update_onDelete' => ['godspeed.flametreecms.delete_producers']
    ];
    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {
        parent::__construct();
        $this->checkPermissions();

        BackendMenu::setContext('GodSpeed.FlametreeCMS', 'flametreecms', 'producers');
    }
}
