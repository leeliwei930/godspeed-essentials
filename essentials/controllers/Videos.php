<?php namespace GodSpeed\Essentials\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use GodSpeed\Essentials\Repositories\VideoRepository;
use GodSpeed\Essentials\Traits\HasBackendPermissions;

/**
 * Video Back-end Controller
 */
class Videos extends Controller
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
        'index' => ['godspeed.essentials.browse_videos'],
        'preview' => ['godspeed.essentials.browse_videos'],
        'create' => ['godspeed.essentials.create_videos'],
        'update' => ['godspeed.essentials.edit_videos'],
        'create_onSave' => ['godspeed.essentials.create_videos'],
        'update_onSave' => ['godspeed.essentials.edit_videos'],
        'update_onDelete' => ['godspeed.essentials.delete_videos']
    ];

    public function __construct()
    {
        parent::__construct();
        $this->checkPermissions();
        BackendMenu::setContext('GodSpeed.Essentials', 'essentials', 'videos');
    }


}
