<?php namespace GodSpeed\FlametreeCMS\Components;

use GodSpeed\FlametreeCMS\Models\Referral;
use Illuminate\Contracts\Validation\Rule;
use Lang;
use Auth;
use Mail;
use Event;
use Flash;
use Input;
use Request;
use Redirect;
use Validator;
use ValidationException;
use ApplicationException;
use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use RainLab\User\Models\Settings as UserSettings;
use Exception;

/**
 * Account component
 *
 * Allows users to register, sign in and update their account. They can also
 * deactivate their account and resend the account verification email.
 */
class ReferralSignUp extends \RainLab\User\Components\Account
{
    public function componentDetails()
    {
        return [
            'name' => /*Account*/ 'rainlab.user::lang.account.account',
            'description' => /*User management form.*/ 'rainlab.user::lang.account.account_desc'
        ];
    }

    public function defineProperties()
    {
        return [
            'redirect' => [
                'title' => /*Redirect to*/ 'rainlab.user::lang.account.redirect_to',
                'description' => /*Page name to redirect to after update, sign in or registration.*/ 'rainlab.user::lang.account.redirect_to_desc',
                'type' => 'dropdown',
                'default' => ''
            ],
            'paramCode' => [
                'title' => /*Activation Code Param*/ 'rainlab.user::lang.account.code_param',
                'description' => /*The page URL parameter used for the registration activation code*/ 'rainlab.user::lang.account.code_param_desc',
                'type' => 'string',
                'default' => 'code'
            ],
            'forceSecure' => [
                'title' => /*Force secure protocol*/ 'rainlab.user::lang.account.force_secure',
                'description' => /*Always redirect the URL with the HTTPS schema.*/ 'rainlab.user::lang.account.force_secure_desc',
                'type' => 'checkbox',
                'default' => 0
            ],
        ];
    }

    public function onRegister()
    {
        try {
            if (!$this->canRegister()) {
                throw new ApplicationException(Lang::get(/*Registrations are currently disabled.*/ 'rainlab.user::lang.account.registration_disabled'));
            }

            /*
             * Validate input
             */
            $data = post();

            if (!array_key_exists('password_confirmation', $data)) {
                $data['password_confirmation'] = post('password');
            }

            $rules = [
                'name' => 'required',
                'email' => 'required|email|between:6,255',
                'password' => 'required|between:4,255|confirmed'
            ];

            if ($this->loginAttribute() == UserSettings::LOGIN_USERNAME) {
                $rules['username'] = 'required|between:2,255';
            }

            $referralCodeValidator =


            $rules['referral_code'] = [
                'required', function ($attribute, $value, $fail) {
                    $referralCode = Referral::findByCode($value);
                    if (is_null($referralCode)) {
                        $fail('The ' . $attribute . ' is not exists');
                        return;
                    }
                    if ($referralCode->isExpired()) {
                        $fail('The ' . $attribute . ' is expired.');
                        return;

                    }

                    if ($referralCode->capped) {
                        if ($referralCode->usage_left <= 0) {
                            $fail('The ' . $attribute . ' is fully redeemed');
                            return;

                        }
                    }


                }
            ];


            $validation = Validator::make($data, $rules);


            if ($validation->fails()) {
                throw new ValidationException($validation);
            }


            /*
             * Register user
             */
            Event::fire('rainlab.user.beforeRegister', [&$data]);
            $referralData = Referral::findByCode($data['referral_code']);

            unset($data['referral_code']);

            $requireActivation = UserSettings::get('require_activation', true);
            $automaticActivation = UserSettings::get('activate_mode') == UserSettings::ACTIVATE_AUTO;
            $userActivation = UserSettings::get('activate_mode') == UserSettings::ACTIVATE_USER;
            $user = Auth::register($data, $automaticActivation);

            $user->referral_id = optional($referralData)->id;
            $referralData->updateUsageLeft();

            Event::fire('rainlab.user.register', [$user, $data]);

            /*
             * Activation is by the user, send the email
             */
            if ($userActivation) {
                $this->sendActivationEmail($user);

                Flash::success(Lang::get(/*An activation email has been sent to your email address.*/ 'rainlab.user::lang.account.activation_email_sent'));
            }

            /*
             * Automatically activated or not required, log the user in
             */
            if ($automaticActivation || !$requireActivation) {
                Auth::login($user);
            }

            /*
             * Redirect to the intended page after successful sign in
             */
            if ($redirect = $this->makeRedirection(true)) {
                return $redirect;
            }
        } catch (Exception $ex) {
            if (Request::ajax()) {
                throw $ex;
            } else {
                Flash::error($ex->getMessage());
            }
        }
    }
}
