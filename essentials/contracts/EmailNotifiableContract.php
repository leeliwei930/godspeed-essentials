<?php
namespace GodSpeed\Essentials\Contracts;

interface EmailNotifiableContract
{
    public function sendMailNotification($email, $template, $viewArguments, $ccList = []);
}
