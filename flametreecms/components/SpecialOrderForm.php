<?php namespace GodSpeed\FlametreeCMS\Components;

use Backend\Facades\Backend;
use Cms\Classes\ComponentBase;
use GodSpeed\FlametreeCMS\Models\SpecialOrder;
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
        $request = post();

        $specialOrder = SpecialOrder::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'message' => $request['message'],
            'phone_number' => $request['phone_number']
        ]);

        if($this->property('Enable email forwarding') == 1){
            $this->sendToReceiver($specialOrder);
        }

        if($this->property('Resend Notification To Customer') == 1){
            $this->sendBackToSender($specialOrder);
        }
    }

    public function sendBackToSender($order)
    {
        Mail::send( "godspeed.flametreecms::mail.templates.sender-template", [ 'order' => $order->toArray()] , function($message) use($order){
            $message->to($order['email']);
        });

    }

    public function sendToReceiver($order){
        $receiver = $this->property("To");
        $ccList = $this->preprocessingCCList();
        $mailActions = [
            'markAsRead' => Backend::url('godspeed/flametreecms/specialorders/markAsRead/'.$order->id),
            'viewInfo' => Backend::url('godspeed/flametreecms/specialorders/preview/'.$order->id)
        ];
        Mail::send("godspeed.flametreecms::mail.templates.receiver-template", [
            'order' => $order->toArray(),
            'mailActions' => $mailActions
        ],
            function($message) use($receiver, $ccList){
                $message->to($receiver);
                $message->cc($ccList);
        });

    }

    protected function preprocessingCCList(){
        $emails = $this->property('CC');
        $emails= str_replace(" ", "", $emails);
        $emailList = explode(",", $emails);

        return $emailList;

    }
}
