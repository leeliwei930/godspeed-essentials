<?php namespace GodSpeed\FlametreeCMS\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Trainings Back-end Controller
 */
class Trainings extends Controller
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

        BackendMenu::setContext('GodSpeed.FlametreeCMS', 'flametreecms', 'trainings');
    }
}
