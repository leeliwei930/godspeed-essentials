<?php namespace GodSpeed\FlametreeCMS;

use Backend;
use BackendMenu;
use GodSpeed\FlametreeCMS\Console\Install;
use GodSpeed\FlametreeCMS\Console\Test;
use GodSpeed\FlametreeCMS\Console\Uninstall;
use GodSpeed\FlametreeCMS\Models\ProducerCategory;
use GodSpeed\FlametreeCMS\Policies\PortalBlogPostPolicy;
use GodSpeed\FlametreeCMS\Traits\AcceptanceTestingTrait;
use GodSpeed\FlametreeCMS\Utils\LazyLoad\AttachmentPlaceholderGenerator;
use GodSpeed\FlametreeCMS\Utils\Lazyload\LazyloadImage;
use GodSpeed\FlametreeCMS\Models\ProducerCategory as ProducerCategoryModel;
use Illuminate\Support\Facades\Event;


use RainLab\Blog\Models\Category;
use RainLab\User\Models\User;
use RainLab\User\Models\UserGroup;
use System\Classes\PluginBase;
use RainLab\User\Controllers\Users as RainLabUsersController;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use System\Classes\PluginManager;

/**
 * flametreeCMS Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    use AcceptanceTestingTrait;
    public $require = [
        'RainLab.User',
        'RainLab.Blog',
        'RainLab.Pages',
        'SureSoftware.PowerSEO',
        'RainLab.MailChimp',
        'AnandPatel.WysiwygEditors'
    ];

    /**
     * @return array
     */

    /**
     * @param $pluginName
     * @return mixed
     * @throws \Exception
     */


    public function pluginDetails()
    {
        return [
            'name' => 'GodSpeed CMS',
            'description' => 'No description provided yet...',
            'author' => 'GodSpeed',
            'icon' => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
        app(EloquentFactory::class)->load(plugins_path('godspeed/flametreecms/database/factories'));
        $this->registerConsoleCommand('flametreecms:install', Install::class);
        $this->registerConsoleCommand('flametreecms:uninstall', Uninstall::class);
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     * @throws \Exception
     */
    public function boot()
    {
        // extend users importer
        $pluginManagerInstance = PluginManager::instance();
        if ($pluginManagerInstance->hasPlugin('RainLab.User')) {
            if (\Schema::hasTable('users')) {
                $this->extendingRainLabUserPlugin();
            }
        }


        if ($pluginManagerInstance->hasPlugin('RainLab.Pages')) {
            $this->extendPagesMenuPluginBehavior();
        }

        if ($pluginManagerInstance->hasPlugin('RainLab.Blog')) {
            if (\Schema::hasTable('rainlab_blog_categories') && \Schema::hasColumn('rainlab_blog_categories', 'user_group')) {
                $this->extendBlogCategoriesFormField();
            }
        }

        if(env('APP_ENV') === 'acceptance'){
            $this->bootAcceptanceTesting();
        }



    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            "GodSpeed\FlametreeCMS\Components\ProducerCategoryList" => "ProducerCategoryList",
            "GodSpeed\FlametreeCMS\Components\SpecialOrderForm" => "SpecialOrderForm",
            "GodSpeed\FlametreeCMS\Components\VideoSection" => "VideoSection",
            "GodSpeed\FlametreeCMS\Components\ImageSlider" => "ImageSlider",
            "GodSpeed\FlametreeCMS\Components\AllProducer" => "AllProducer",
            "GodSpeed\FlametreeCMS\Components\TrendingAnnouncement" => "TrendingAnnouncement",
            "GodSpeed\FlametreeCMS\Components\BusinessContact" => "BusinessContact",
            "GodSpeed\FlametreeCMS\Components\TeamMember" => "TeamMember",
            "GodSpeed\FlametreeCMS\Components\VolunteerResetPassword" => "VolunteerResetPassword",
            "GodSpeed\FlametreeCMS\Components\PrivateAnnouncements" => "PrivateAnnouncements",
            "GodSpeed\FlametreeCMS\Components\UpdateProfile" => "UpdateProfile",


            "GodSpeed\FlametreeCMS\Components\PortalRecentPrivateAnnouncements" => "PortalRecentPrivateAnnouncements",
            "GodSpeed\FlametreeCMS\Components\ReferralSignUp" => "ReferralSignUp",

            "GodSpeed\FlametreeCMS\Components\Events" => "Events",
            "GodSpeed\FlametreeCMS\Components\Event" => "Event",
            "GodSpeed\FlametreeCMS\Components\UpcomingEvents" => "UpcomingEvents",
            "GodSpeed\FlametreeCMS\Components\Trainings" => "Trainings",
            "GodSpeed\FlametreeCMS\Components\Training" => "Training",

            "GodSpeed\FlametreeCMS\Components\FaqCategories" => "FaqCategories",
            "GodSpeed\FlametreeCMS\Components\FaqCategory" => "FaqCategory",

        ]; // Remove this line to activate

//        return [
//            'GodSpeed\FlametreeCMS\Components\MyComponent' => 'myComponent',
//        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
//        return []; // Remove this line to activate

        return config('godspeed.flametreecms::permissions');
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {

        return [
            'flametreecms' => [
                'label' => 'GodSpeed CMS',
                'url' => Backend::url('godspeed/flametreecms/producers'),
                'icon' => 'icon-window-restore',
                'order' => 500,
                "sideMenu" => [
                    "producers" => [
                        "label" => "Producers",
                        "icon" => 'icon-address-book',
                        "url" => Backend::url("godspeed/flametreecms/producers"),
                        'permissions' => [
                            'godspeed.flametreecms.browse_producers'
                        ]
                    ],
                    "products" => [
                        "label" => "Products",
                        "icon" => 'icon-shopping-bag',
                        "url" => Backend::url("godspeed/flametreecms/products"),
                        'permissions' => [
                            'godspeed.flametreecms.browse_products'
                        ]
                    ],
                    "specialorders" => [
                        'label' => 'Special Orders',
                        'icon' => 'icon-inbox',
                        'url' => Backend::url('godspeed/flametreecms/specialorders'),
                        'permissions' => [
                            'godspeed.flametreecms.browse_special_orders'
                        ]
                    ],
                    "referrals" => [
                        'label' => "Referrals",
                        'icon' => 'icon-ticket',
                        'url' => Backend::url('godspeed/flametreecms/referrals'),
                        'permissions' => [
                            'godspeed.flametreecms.browse_referrals'
                        ]

                    ],
                    "events" => [
                        'label' => "Events",
                        'icon' => 'icon-calendar',
                        'url' => Backend::url('godspeed/flametreecms/events'),
                        'permissions' => [
                            'godspeed.flametreecms.browse_events'
                        ]
                    ],
                    'qrcodes' => [
                        'label' => "QR Codes",
                        'icon' => 'icon-qrcode',
                        'url' => Backend::url('godspeed/flametreecms/qrcodes'),
                        'permissions' => [
                            'godspeed.flametreecms.browse_qrcodes'
                        ]
                    ],
                    "trainings" => [
                        'label' => "Trainings",
                        'icon' => 'icon-rocket',
                        'url' => Backend::url('godspeed/flametreecms/trainings'),
                        'permissions' => [
                            'godspeed.flametreecms.browse_trainings'
                        ]
                    ],
                    "videos" => [
                        'label' => "Videos",
                        'icon' => 'icon-film',
                        'url' => Backend::url('godspeed/flametreecms/videos'),
                        'permissions' => [
                            'godspeed.flametreecms.browse_videos'
                        ]
                    ],
                    'faqs' => [
                        "label" => "Faqs",
                        "icon" => "icon-question-circle",
                        "url" => Backend::url("godspeed/flametreecms/faqs"),
                        'permissions' => [
                            'godspeed.flametreecms.browse_faqs'
                        ]
                    ],
                    "imagesliders" => [
                        "label" => "Carousel/Image Slider",
                        "icon" => 'icon-picture-o',
                        "url" => Backend::url("godspeed/flametreecms/imagesliders"),
                        'permissions' => [
                            'godspeed.flametreecms.browse_image_sliders'
                        ]
                    ],

                ]
            ],
        ];
    }

    public function registerMailTemplates()
    {
        return [
            'godspeed.flametreecms::mail.templates.receiver-template',
            'godspeed.flametreecms::mail.templates.sender-template',
            'godspeed.flametreecms::mail.templates.volunteer-invitation'
        ];
    }

    public function registerMailLayouts()
    {
        return [
            'flametreeCMS-frontend' => "godspeed.flametreecms::mail.layouts.flametreeCMS-frontend-layouts"
        ];
    }

    public function registerMailPartials()
    {
        return [
            'flametreecms-frontend-header' => "godspeed.flametreecms::mail.partials.flametreeCMS-frontend-header",
            'flametreecms-frontend-footer' => "godspeed.flametreecms::mail.partials.flametreeCMS-frontend-footer"
        ];
    }

    public function registerSettings()
    {
        return [
            'flametree-cms-api-settings' => [
                'label' => 'FlameTree CMS Settings',
                'description' => 'Manage the general settings of the FlametreeCMS',
                'category' => 'FlametreeCMS',
                'icon' => 'icon-plug',
                'class' => 'GodSpeed\FlametreeCMS\Models\Settings',
                'order' => 500,
                'keywords' => 'api settings'
            ]
        ];
    }

    public function registerMarkupTags()
    {
        return [
            'filters' => [
                'resize' => function ($file_path, $width = null, $height = null, $options = []) {
                    $image = new LazyloadImage($file_path);
                    return $image->resize($width, $height, $options);
                },
            ]
        ];
    }

    public function extendingRainLabUserPlugin()
    {
        Event::listen('backend.menu.extendItems', function ($manager) {


            $manager->addSideMenuItems('RainLab.User', 'user', [
                'imports' => [
                    "label" => "Import Volunteers",
                    "icon" => 'icon-upload',
                    "url" => Backend::url("rainlab/user/users/import"),
                ],
                'exports' => [
                    "label" => "Export Volunteers",
                    "icon" => 'icon-download',
                    "url" => Backend::url("rainlab/user/users/export"),
                ]
            ]);
        });
        RainLabUsersController::extend(function ($controller) {
            $controller->implement[] = 'Backend.Behaviors.ImportExportController';
            $controller->addDynamicProperty(
                'importExportConfig',
                '$/godspeed/flametreecms/controllers/volunteers/config_import_export.yaml'
            );
            $controller->addViewPath("$/godspeed/flametreecms/views/rainlabUser");
            BackendMenu::setContext('RainLab.User', 'user', 'imports');
        });

        UserGroup::extend(function ($model) {
            $model->belongsToMany['events'] = [
                \GodSpeed\FlametreeCMS\Models\Event::class,
                'table' => 'godspeed_flametreecms_events_roles', 'key' => 'member_role_id', 'other_key' => 'event_id'
            ];

            $model->belongsToMany['trainings'] = [
                \GodSpeed\FlametreeCMS\Models\Training::class,
                'table' => 'godspeed_flametreecms_roles_trainings', 'key' => 'role_id', 'other_key' => 'training_id'
            ];
            $model->hasOne['referral'] = [
                \GodSpeed\FlametreeCMS\Models\Referral::class
            ];

        });


        User::extend(function ($model) {
            $model->addFillable('phone_number');
            $model->addFillable('referral_id');

            $model->hasOne['referral'] = [
                \GodSpeed\FlametreeCMS\Models\Referral::class
            ];
        });


        RainLabUsersController::extendFormFields(function ($form, $model, $context) {
            if (!$model instanceof User) {
                return;
            }

            $model->rules['phone_number'] = "between:8,20";
            $form->addFields([
                'phone_number' => [
                    "label" => "Phone Number"
                ]
            ]);
        });

        RainLabUsersController::extendListColumns(function ($list, $model) {
            $list->addColumns(['phone_number' => [
                'searchable' => true,
                'label' => "Phone Number",
            ]]);
        });
    }

    public function extendPagesMenuPluginBehavior()
    {
        Event::listen('pages.menuitem.listTypes', function () {
            return [
                'all-producer-category' => "All Producer Category",

            ];
        });

        Event::listen('pages.menuitem.resolveItem', function ($type, $item, $url, $theme) {
            if ($type == 'all-producer-category') {
                return ProducerCategoryModel::resolveMenuItem($item, $url, $theme);
            }
        });
    }


    public function extendBlogCategoriesFormField()
    {

        Category::extend(function ($model) {
            $model->bindEvent('model.form.filterFields', function ($widget, $fields, $context) use ($model) {
                switch ($context) {
                    case "create":
                        if ($fields->required_auth->value === "0") {
                            $fields->user_group->value = null;
                            $fields->user_group->hidden = true;
                        } else {
                            $model->user_group = null;
                            $fields->user_group->hidden = false;
                        }
                        break;

                    case "update":
                        if ($fields->user_group->value != null) {
                            $fields->required_auth->value = "1";
                        }
                        if ($fields->required_auth->value === "0") {
                            $fields->user_group->value = null;
                            $fields->user_group->hidden = true;
                        } else {
                            $fields->user_group->hidden = false;
                        }
                        break;
                }
            });
            $model->bindEvent('model.beforeValidate', function () use ($model) {
                if ($model->required_auth == 0) {
                    $model->user_group = null;
                }
                $model->rules['user_group'] = 'nullable|exists:user_groups,id';

                unset($model->required_auth);
            });
        });


        Event::listen('backend.form.extendFields', function ($widget) {

            if (!$widget->getController() instanceof \RainLab\Blog\Controllers\Categories) {
                return;
            }

            if (!$widget->model instanceof \RainLab\Blog\Models\Category) {
                return;
            }


            $widget->addFields([
                'featured_image' => [
                    'label' => 'Featured Image',
                    'type' => 'mediafinder'
                ],
                'required_auth' => [
                    "type" => "checkbox",
                    "label" => "Restricted Access",
                    'span' => "left"

                ],
                'user_group' => [
                    'label' => "Only visible to",
                    "type" => "dropdown",
                    "options" => UserGroup::pluck('name', 'id'),
                    'span' => 'right',


                    "trigger" => [

                        'action' => 'show',
                        'field' => "required_auth",
                        'condition' => "checked"
                    ]

                ]
            ]);
        });
    }


}
