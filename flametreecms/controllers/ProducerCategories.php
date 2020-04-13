<?php namespace GodSpeed\FlametreeCMS\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use GodSpeed\FlametreeCMS\Traits\HasBackendPermissions;
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
        'index' => ['godspeed.flametreecms.browse_producers'],
        'preview' => ['godspeed.flametreecms.browse_producers'],
        'create' => ['godspeed.flametreecms.create_producers'],
        'update' => ['godspeed.flametreecms.edit_producers'],
        'create_onSave' => ['godspeed.flametreecms.create_producers'],
        'update_onSave' => ['godspeed.flametreecms.edit_producers'],
        'update_onDelete' => ['godspeed.flametreecms.delete_producers']
    ];
    public $rules = [
        "name" => [
            "required",
            "unique:godspeed_flametreecms_producer_categories,name"
        ]
    ];


    public function __construct()
    {
        $this->checkPermissions();
        parent::__construct();

        BackendMenu::setContext('GodSpeed.FlametreeCMS', 'flametreecms', 'producers');
    }
}
