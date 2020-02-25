<?php namespace GodSpeed\FlametreeCMS\Components;

use Auth;
use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
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
            'name'        => 'UpdateProfile Component',
            'description' => 'No description provided yet...'
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
                'required' , 'between:7,15'
            ],
            'current_password' => [
                'required_if:reset_password,on' ,
                function($attribute, $value, $fail)
                {
                    return $this->verifyOldPassword($attribute, $value, $fail);
                }
            ],
            'new_password' => [
                'required_if:reset_password,on' , 'between:4,255', 'confirmed'
            ]
        ];
        $validation = \Validator::make(post(), $validationRules);
        if ($validation->fails()) {
            throw new ValidationException($validation);
        }
        $user->update([
            'name' => post('name'),
            'surname' => post('surname'),
            'phone_number' => post('phone_number'),
        ]);


        if (post('reset_password') === 'on') {
            $user->password = post('password_confirmation');
            Auth::login($user->reload(), true);
        }

        if (post('avatar') !== '') {
            $avatar = Image::make(post('avatar'))->resize(150,150)->encode('jpg', 90);
            $filename =  md5(time().$avatar->getEncoded()).".jpg";
            $file = new \System\Models\File();
            $file->fromData($avatar->getEncoded(), $filename);
            $user->avatar = $file;
        }
        $user->save();








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
