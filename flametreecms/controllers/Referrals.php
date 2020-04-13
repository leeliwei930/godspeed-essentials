<?php namespace GodSpeed\FlametreeCMS\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use GodSpeed\FlametreeCMS\Traits\HasBackendPermissions;
/**
 * Referrals Back-end Controller
 */
class Referrals extends Controller
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
        'index' => ['godspeed.flametreecms.browse_referrals'],
        'preview' => ['godspeed.flametreecms.browse_referrals'],
        'create' => ['godspeed.flametreecms.create_referrals'],
        'update' => ['godspeed.flametreecms.edit_referrals'],
        'create_onSave' => ['godspeed.flametreecms.create_referrals'],
        'update_onSave' => ['godspeed.flametreecms.edit_referrals'],
        'update_onDelete' => ['godspeed.flametreecms.delete_referrals']
    ];


    public function __construct()
    {
        parent::__construct();
        $this->checkPermissions();

        BackendMenu::setContext('GodSpeed.FlametreeCMS', 'flametreecms', 'referrals');
    }


}
