<?php namespace GodSpeed\Essentials\Components;

use Auth;
use Cms\Classes\Page;
use Lang;
use Mail;
use Validator;
use ValidationException;
use ApplicationException;
use RainLab\User\Components\ResetPassword;
use RainLab\User\Models\User as UserModel;

class VolunteerResetPassword extends ResetPassword
{

    public function defineProperties()
    {
        return [
            'paramCode' => [
                'title'       => /*Reset Code Param*/'rainlab.user::lang.reset_password.code_param',
                'description' => /*The page URL parameter used for the reset code*/'rainlab.user::lang.reset_password.code_param_desc',
                'type'        => 'string',
                'default'     => 'code'
            ],
            'redirect_url' => [
                'title' => "Redirect URL after reset",
                "description" => "The page will be redirected after the password get updated",
                'type' => 'dropdown',
                'options' =>  Page::getNameList()
            ],
            'success_param' => [
                'title' => "Redirect URL parameter code",
                "description" => "The parameter key",
                "default" => 'passwordUpdateSuccess'
            ],
            'success_message' => [
                'title' => "Success message after redirected",
                'description' => "The message will be attach on the success_param query",
                'default' => "Your password has been updated, please login."
            ],
            'reset_mail_sent_message' => [
                'title' => "Mail sent successful message",
                'description' => "The message that will be shown after the password reset mail sent successfully",
                'default' => "A reset password instruction has been sent out to your inbox."
            ]
        ];
    }
    public function onResetPassword()
    {

        $rules = [
           'code'     => ['required'],
           'password' => [
               'required' , "between:" . UserModel::getMinPasswordLength() . ",255", "confirmed"
           ]

        ];

        $validation = Validator::make(post(), $rules);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }

        $errorFields = ['code' => Lang::get(/*Invalid activation code supplied.*/'rainlab.user::lang.account.invalid_activation_code')];

        /*
        * Break up the code parts
        */
        $parts = explode('!', post('code'));
        if (count($parts) != 2) {
            throw new ValidationException($errorFields);
        }

        list($userId, $code) = $parts;

        if (!strlen(trim($userId)) || !strlen(trim($code)) || !$code) {
            throw new ValidationException($errorFields);
        }

        if (!$user = Auth::findUserById($userId)) {
            throw new ValidationException($errorFields);
        }

        if (!$user->attemptResetPassword($code, post('password'))) {
            throw new ValidationException($errorFields);
        }
    }

    public function getRedirectUrl(){
        $successParamKey = $this->property('success_param');
        $successMessage = $this->property('success_message');
        return Page::url($this->property('redirect_url'))."?$successParamKey=$successMessage";
    }

    public function getResetPasswordMailSentRedirectUrl(){
        $successParamKey = $this->property('success_param');
        $successMessage = $this->property('reset_mail_sent_message');
        return Page::url($this->property('redirect_url'))."?$successParamKey=$successMessage";
    }



}
