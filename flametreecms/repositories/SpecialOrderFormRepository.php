<?php

namespace GodSpeed\FlametreeCMS\Repositories;

use GodSpeed\FlametreeCMS\Contracts\EmailNotifiableContract;
use GodSpeed\FlametreeCMS\Models\SpecialOrder;
use Illuminate\Support\Facades\Mail;
use Backend\Facades\Backend;

class SpecialOrderFormRepository implements EmailNotifiableContract
{

    public $fields;
    public $props;

    public function __construct($fieldData, $props)
    {
        $this->fields = $fieldData;
        $this->props = $props;
    }

    public function placeOrder()
    {

        $order = SpecialOrder::create($this->fields);
        $request = $this->fields;
        
        // send to receiver
        if ($this->props['Enable email forwarding'] == 1) {
            $this->sendMailNotification(
                $this->props['To'],
                "godspeed.flametreecms::mail.templates.receiver-template",
                [
                    'order' => $order->toArray(),
                    'mailActions' => [
                        'markAsRead' => Backend::url('godspeed/flametreecms/specialorders/markAsRead/'.$order->id),
                        'viewInfo' => Backend::url('godspeed/flametreecms/specialorders/preview/'.$order->id)
                    ]
                ]
            );
        }
        
        // send to sender customer
        if ($this->props['Resend Notification To Customer'] == 1) {
            $this->sendMailNotification(
                $order->email,
                "godspeed.flametreecms::mail.templates.sender-template",
                [
                    'order' => $order->toArray()
                ],
                []
            );
        }

        if (!is_null($order)) {
            \Flash::success("We have receive your order");
        } else {
            \Flash::error("There is a problem while saving your special order message. Please Try Again Later");
        }
    }

    public function sendMailNotification($email, $template, $viewArguments, $ccList = [])
    {
       
        Mail::send($template, $viewArguments, function ($message) use ($email, $ccList) {
            $message->to($email);
            if (count($ccList)) {
                $message->cc($ccList);
            }
        });
    }
}
