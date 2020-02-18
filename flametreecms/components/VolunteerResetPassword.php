<?php namespace GodSpeed\FlametreeCMS\Components;


use Auth;
use Lang;
use Mail;
use Validator;
use ValidationException;
use ApplicationException;
use RainLab\User\Components\ResetPassword;
use RainLab\User\Models\User as UserModel;

class VolunteerResetPassword extends ResetPassword
{
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
}
