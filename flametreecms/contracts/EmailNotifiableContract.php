<?php
namespace GodSpeed\FlametreeCMS\Contracts;

interface EmailNotifiableContract
{
    public function sendMailNotification($email, $template, $viewArguments, $ccList = []);
}
