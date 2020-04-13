<?php namespace GodSpeed\FlametreeCMS\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use GodSpeed\FlametreeCMS\Traits\HasBackendPermissions;

/**
 * Image Sliders Back-end Controller
 */
class ImageSliders extends Controller
{
    use HasBackendPermissions;

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $actionPermissions = [
        'index' => ['godspeed.flametreecms.browse_image_sliders'],
        'preview' => ['godspeed.flametreecms.browse_image_sliders'],
        'create' => ['godspeed.flametreecms.create_image_sliders'],
        'update' => ['godspeed.flametreecms.edit_image_sliders'],
        'create_onSave' => ['godspeed.flametreecms.create_image_sliders'],
        'update_onSave' => ['godspeed.flametreecms.edit_image_sliders'],
        'update_onDelete' => ['godspeed.flametreecms.delete_image_sliders']
    ];
    public function __construct()
    {
        parent::__construct();
        $this->checkPermissions();
        BackendMenu::setContext('GodSpeed.FlametreeCMS', 'flametreecms', 'imagesliders');
    }
}
