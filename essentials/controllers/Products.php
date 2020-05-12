<?php namespace GodSpeed\Essentials\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use GodSpeed\Essentials\Traits\HasBackendPermissions;

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
        'index' => ['godspeed.essentials.browse_products'],
        'preview' => ['godspeed.essentials.browse_products'],
        'create' => ['godspeed.essentials.create_products'],
        'update' => ['godspeed.essentials.edit_products'],
        'create_onSave' => ['godspeed.essentials.create_products'],
        'update_onSave' => ['godspeed.essentials.edit_products'],
        'update_onDelete' => ['godspeed.essentials.delete_products']
    ];

    public $bodyClass = "compact-container";
    public function __construct()
    {
        parent::__construct();
        $this->checkPermissions();
        BackendMenu::setContext('GodSpeed.Essentials', 'essentials', 'products');
    }
}
