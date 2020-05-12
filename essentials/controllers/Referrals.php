<?php namespace GodSpeed\Essentials\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use GodSpeed\Essentials\Traits\HasBackendPermissions;
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
        'index' => ['godspeed.essentials.browse_referrals'],
        'preview' => ['godspeed.essentials.browse_referrals'],
        'create' => ['godspeed.essentials.create_referrals'],
        'update' => ['godspeed.essentials.edit_referrals'],
        'create_onSave' => ['godspeed.essentials.create_referrals'],
        'update_onSave' => ['godspeed.essentials.edit_referrals'],
        'update_onDelete' => ['godspeed.essentials.delete_referrals']
    ];


    public function __construct()
    {
        parent::__construct();
        $this->checkPermissions();

        BackendMenu::setContext('GodSpeed.Essentials', 'essentials', 'referrals');
    }


}
