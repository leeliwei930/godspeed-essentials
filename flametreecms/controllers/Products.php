<?php namespace GodSpeed\FlametreeCMS\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use GodSpeed\FlametreeCMS\Traits\HasBackendPermissions;

/**
 * Products Back-end Controller
 */
class Products extends Controller
{
    use HasBackendPermissions;

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController',
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';

    public $actionPermissions = [
        'index' => ['godspeed.flametreecms.browse_products'],
        'preview' => ['godspeed.flametreecms.browse_products'],
        'create' => ['godspeed.flametreecms.create_products'],
        'update' => ['godspeed.flametreecms.edit_products'],
        'create_onSave' => ['godspeed.flametreecms.create_products'],
        'update_onSave' => ['godspeed.flametreecms.edit_products'],
        'update_onDelete' => ['godspeed.flametreecms.delete_products']
    ];

    public $bodyClass = "compact-container";
    public function __construct()
    {
        parent::__construct();
        $this->checkPermissions();
        BackendMenu::setContext('GodSpeed.FlametreeCMS', 'flametreecms', 'products');
    }
}
