<?php namespace GodSpeed\Essentials\Components;


use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use RainLab\User\Facades\Auth;
use ValidationException;
use Input;
use Lang;
use RainLab\User\Components\Account;
use Intervention\Image\Facades\Image;

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
            'name' => 'UpdateProfile',
            'description' => 'Render an update profile form'
        ];
    }

    public function verifyOldPassword($attribute, $value, $fail)
    {
        $isAuthorised = Hash::check($value, $this->user()->password);
        if (!$isAuthorised) {
            $fail('The current password is not correct');
        }
    }

    public function onUpdate()
    {

        if (!$user = $this->user()) {

            return;
        }


        $validationRules = [
            'name' => [
                'required', 'between:2,255'
            ],
            'surname' => [
                'required', 'between:2,255'
            ],
            'reset_password' => [
                'in:on,off'
            ],

            'phone_number' => [
                'required', 'between:7,15'
            ],
            'current_password' => [
                'required_if:reset_password,on',
                function ($attribute, $value, $fail) {
                    return $this->verifyOldPassword($attribute, $value, $fail);
                }
            ],
            'new_password' => [
                'required_if:reset_password,on', 'between:4,255', 'confirmed'
            ]
        ];
        $validation = \Validator::make(post(), $validationRules);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }


        $user->update([
            'name' => post('name'),
            'surname' => post('surname'),
            'godspeed_essentials_phone_number' => post('phone_number'),
        ]);

        $hasRequestToChangedPassword = post('reset_password') === "on";

        if ($hasRequestToChangedPassword) {

            $user->password = post('new_password_confirmation');
            // Force update the password without any default model validation level

        }

        if (\Input::has('avatar')) {
            $avatar = Image::make(\Input::file('avatar'))->resize(150, 150)->encode('jpg', 90);
            $filename = md5(time() . $avatar->getEncoded()) . ".jpg";

            $file = (new \System\Models\File)->fromData($avatar->getEncoded(), $filename);
            $file->save();
            $user->avatar = $file;
        }

        $user->forceSave();

        \Flash::success(post('flash', "Profile updated successfully."));
        if ($hasRequestToChangedPassword) {
            Auth::login($user->reload(), true);
        }
        /*
         * Redirect
         */
        if ($redirect = $this->makeRedirection()) {
            return $redirect;
        }

        $this->prepareVars();

    }
}
