<?php namespace GodSpeed\Essentials\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use GodSpeed\Essentials\Traits\HasBackendPermissions;
use October\Rain\Database\Traits\Validation;

/**
 * Product Categories Back-end Controller
 */
class ProducerCategories extends Controller
{
    use Validation;
    use HasBackendPermissions;


    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        "Backend.Behaviors.RelationController"
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';
    public $actionPermissions = [
        'index' => ['godspeed.essentials.browse_producers'],
        'preview' => ['godspeed.essentials.browse_producers'],
        'create' => ['godspeed.essentials.create_producers'],
        'update' => ['godspeed.essentials.edit_producers'],
        'create_onSave' => ['godspeed.essentials.create_producers'],
        'update_onSave' => ['godspeed.essentials.edit_producers'],
        'update_onDelete' => ['godspeed.essentials.delete_producers']
    ];
    public $rules = [
        "name" => [
            "required",
            "unique:godspeed_essentials_producer_categories,name"
        ]
    ];


    public function __construct()
    {
        $this->checkPermissions();
        parent::__construct();

        BackendMenu::setContext('GodSpeed.Essentials', 'essentials', 'producers');
    }
}
