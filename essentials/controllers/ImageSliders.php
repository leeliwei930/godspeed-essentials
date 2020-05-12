<?php namespace GodSpeed\Essentials\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use GodSpeed\Essentials\Traits\HasBackendPermissions;

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
        'index' => ['godspeed.essentials.browse_image_sliders'],
        'preview' => ['godspeed.essentials.browse_image_sliders'],
        'create' => ['godspeed.essentials.create_image_sliders'],
        'update' => ['godspeed.essentials.edit_image_sliders'],
        'create_onSave' => ['godspeed.essentials.create_image_sliders'],
        'update_onSave' => ['godspeed.essentials.edit_image_sliders'],
        'update_onDelete' => ['godspeed.essentials.delete_image_sliders']
    ];
    public function __construct()
    {
        parent::__construct();
        $this->checkPermissions();
        BackendMenu::setContext('GodSpeed.Essentials', 'essentials', 'imagesliders');
    }
}
