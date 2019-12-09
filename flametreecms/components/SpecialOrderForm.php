<?php namespace GodSpeed\FlametreeCMS\Components;

use Backend\Facades\Backend;
use Cms\Classes\ComponentBase;
use GodSpeed\FlametreeCMS\Models\SpecialOrder;
use GodSpeed\FlametreeCMS\Repositories\SpecialOrderFormRepository;
use Illuminate\Support\Facades\Mail;

class SpecialOrderForm extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'SpecialOrderForm Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [
            "To" => [
                "type" => "text",
                "description" => "The main receiver to receive the special order mail notifications",
            ],
            "CC" => [
                "type" => "text",
                "description" => "CC the incoming special order notification to others people",
                "placeholder" => "Example: abc@example.com , jd@example.com"
            ],
            "Active" => [
                "type" => "checkbox"
            ],
            "Enable email forwarding" => [
                "type" => "checkbox",
                "default" => 0
            ],
            "Resend Notification To Customer" => [
                "type" => "checkbox",
                "default" => 0
            ]
        ];
    }

    public function onSubmit()
    {
        $specialOrderRepo = new SpecialOrderFormRepository(post(), $this->getProperties());
        $specialOrderRepo->placeOrder();
    }
}
