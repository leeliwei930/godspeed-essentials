<?php namespace GodSpeed\FlametreeCMS\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use GodSpeed\FlametreeCMS\Traits\HasBackendPermissions;

/**
 * Video Playlist Back-end Controller
 */
class Playlists extends Controller
{

    use HasBackendPermissions;


    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController',
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = "config_relation.yaml";
    public $actionPermissions = [
        'index' => ['godspeed.flametreecms.browse_videos'],
        'preview' => ['godspeed.flametreecms.browse_videos'],
        'create' => ['godspeed.flametreecms.create_videos'],
        'update' => ['godspeed.flametreecms.edit_videos'],
        'create_onSave' => ['godspeed.flametreecms.create_videos'],
        'update_onSave' => ['godspeed.flametreecms.edit_videos'],
        'update_onDelete' => ['godspeed.flametreecms.delete_videos']
    ];


    public function __construct()
    {
        $this->checkPermissions();
        parent::__construct();

        BackendMenu::setContext('GodSpeed.FlametreeCMS', 'flametreecms', 'videos');
    }
}
