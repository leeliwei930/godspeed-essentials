<?php namespace GodSpeed\FlametreeCMS\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use GodSpeed\FlametreeCMS\Traits\HasBackendPermissions;

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
        'index' => ['godspeed.flametreecms.browse_faqs'],
        'preview' => ['godspeed.flametreecms.browse_faqs'],
        'create' => ['godspeed.flametreecms.create_faqs'],
        'update' => ['godspeed.flametreecms.edit_faqs'],
        'create_onSave' => ['godspeed.flametreecms.create_faqs'],
        'update_onSave' => ['godspeed.flametreecms.edit_faqs'],
        'update_onDelete' => ['godspeed.flametreecms.delete_faqs']
    ];
    public function __construct()
    {
        $this->checkPermissions();

        parent::__construct();

        BackendMenu::setContext('GodSpeed.FlametreeCMS', 'flametreecms', 'faqs');
    }
}
