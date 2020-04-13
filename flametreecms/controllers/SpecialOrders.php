<?php namespace GodSpeed\FlametreeCMS\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use GodSpeed\FlametreeCMS\Models\SpecialOrder;
use Backend\Facades\Backend;
use GodSpeed\FlametreeCMS\Traits\HasBackendPermissions;
use October\Rain\Support\Facades\Flash;

/**
 * Special Orders Back-end Controller
 */
class SpecialOrders extends Controller
{
    use HasBackendPermissions;

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $actionPermissions = [
        'index' => ['godspeed.flametreecms.browse_special_orders'],
        'preview' => ['godspeed.flametreecms.browse_special_orders'],
        'create' => ['godspeed.flametreecms.create_special_orders'],
        'update' => ['godspeed.flametreecms.edit_special_orders'],
        'create_onSave' => ['godspeed.flametreecms.create_special_orders'],
        'update_onSave' => ['godspeed.flametreecms.edit_special_orders'],
        'update_onDelete' => ['godspeed.flametreecms.delete_special_orders']
    ];

    public function __construct()
    {
        parent::__construct();
        $this->checkPermissions();
        BackendMenu::setContext('GodSpeed.FlametreeCMS', 'flametreecms', 'specialorders');
    }

    public function markAsRead($orderID)
    {
        $order = SpecialOrder::findOrFail($orderID);
        $order->is_read = true;
        $order->save();
        Flash::success("Special Order from " . $order->email . " marked as read.");
        return redirect(Backend::url("/godspeed/flametreecms/specialorders/preview/" . $order->id));
    }
}
