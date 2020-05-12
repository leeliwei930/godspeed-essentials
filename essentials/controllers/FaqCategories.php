<?php namespace GodSpeed\Essentials\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use GodSpeed\Essentials\Traits\HasBackendPermissions;

/**
 * Faq Categories Back-end Controller
 */
class FaqCategories extends Controller
{
    use HasBackendPermissions;

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';
    public $actionPermissions = [
        'index' => ['godspeed.essentials.browse_faqs'],
        'preview' => ['godspeed.essentials.browse_faqs'],
        'create' => ['godspeed.essentials.create_faqs'],
        'update' => ['godspeed.essentials.edit_faqs'],
        'create_onSave' => ['godspeed.essentials.create_faqs'],
        'update_onSave' => ['godspeed.essentials.edit_faqs'],
        'update_onDelete' => ['godspeed.essentials.delete_faqs']
    ];
    public function __construct()
    {
        $this->checkPermissions();

        parent::__construct();

        BackendMenu::setContext('GodSpeed.Essentials', 'essentials', 'faqs');
    }
}
