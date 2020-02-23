<?php namespace GodSpeed\FlametreeCMS\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use ValidationException;
use Input;
use Lang;
use RainLab\User\Components\Account;

class UpdateProfile extends Account
{
    public function onToggleShowPasswordForm()
    {

        $this->page['showPasswordForm'] = input('reset_password', "off");

        return ['#change-password-form' => $this->renderPartial('profile-settings/update-password-form', [
            'showPasswordForm' => $this->page['showPasswordForm']
        ])
        ];
    }

    public function componentDetails()
    {
        return [
            'name'        => 'UpdateProfile Component',
            'description' => 'No description provided yet...'
        ];
    }
    public function verifyOldPassword($attribute, $value, $fail)
    {
        $isAuthorised = Hash::check($value, $this->user()->password);
        if (!$isAuthorised) {
            $fail($attribute . ' is not correct');
        }
    }

    public function onUpdate()
    {

        if (!$user = $this->user()) {
            return;
        }

        if (Input::hasFile('avatar')) {
            $user->avatar = Input::file('avatar');
        }

        $validationRules = [
            'name' => [
                'required', 'between:2,255'
            ],
            'surname' => [
                'required', 'between:2,255'
            ],
            'reset_password' => [
                 'boolean'
            ],

            'phone_number' => [
                'required' , 'between:7,15'
            ],
            'current_password' => [
                'required_if:reset_password,on' ,
                $this->verifyOldPassword
            ],
            'new_password' => [
                'required_if:reset_password,true' , 'between:4,255', 'confirmed'
            ]
        ];
        $validation = \Validator::make(post(), $validationRules);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }


        $user->save();







        /*
         * Password has changed, reauthenticate the user
         */
        if (strlen(post('password'))) {
            Auth::login($user->reload(), true);
        }

        \Flash::success(post('flash', Lang::get(/*Settings successfully saved!*/'rainlab.user::lang.account.success_saved')));
        /*
         * Redirect
         */
        if ($redirect = $this->makeRedirection()) {
            return $redirect;
        }

        $this->prepareVars();
    }
}
