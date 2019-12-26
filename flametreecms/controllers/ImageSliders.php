<?php namespace GodSpeed\FlametreeCMS\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Image Sliders Back-end Controller
 */
class ImageSliders extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('GodSpeed.FlametreeCMS', 'flametreecms', 'imagesliders');
    }
}
