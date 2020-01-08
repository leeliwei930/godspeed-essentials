<?php namespace GodSpeed\FlametreeCMS\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use October\Rain\Database\Traits\Validation;

/**
 * Product Categories Back-end Controller
 */
class ProducerCategories extends Controller
{
    use Validation;


    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        "Backend.Behaviors.RelationController"
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';

    public $rules = [
        "name" => [
            "required",
            "unique:godspeed_flametreecms_producer_categories,name"
        ]
    ];


    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('GodSpeed.FlametreeCMS', 'flametreecms', 'producers');
    }
}
