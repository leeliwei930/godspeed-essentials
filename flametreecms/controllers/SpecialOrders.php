<?php namespace GodSpeed\FlametreeCMS\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use GodSpeed\FlametreeCMS\Models\SpecialOrder;
use Backend\Facades\Backend;
use October\Rain\Support\Facades\Flash;

/**
 * Special Orders Back-end Controller
 */
class SpecialOrders extends Controller
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

        BackendMenu::setContext('GodSpeed.FlametreeCMS', 'flametreecms', 'specialorders');
    }

    public function markAsRead($orderID)
    {
        $order = SpecialOrder::findOrFail($orderID);
        $order->is_read = true;
        $order->save();
        Flash::success("Special Order from " . $order->email . " marked as read.");
        return redirect(Backend::url("/godspeed/flametreecms/specialorders/preview/".$order->id));
    }
}
