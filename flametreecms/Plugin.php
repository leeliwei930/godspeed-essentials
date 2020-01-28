<?php namespace GodSpeed\FlametreeCMS;

use Backend;
use BackendMenu;
use GodSpeed\FlametreeCMS\Models\FaqCategory;
use GodSpeed\FlametreeCMS\Models\ProducerCategory;
use GodSpeed\FlametreeCMS\Utils\VideoMeta\Video;
use GodSpeed\FlametreeCMS\Models\Video as VideoModel;
use GodSpeed\FlametreeCMS\Models\ProducerCategory as ProducerCategoryModel;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use October\Rain\Exception\ValidationException;
use RainLab\User\Models\User;
use System\Classes\PluginBase;
use RainLab\User\Controllers\Users as RainLabUsersController;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;

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
    public $require = ['RainLab.User', 'RainLab.Blog', 'RainLab.Pages', 'SureSoftware.PowerSEO', 'RainLab.MailChimp'];

    /**
     * @return array
     */
    public static function pluginDependenciesState()
    {
        return [
            "RainLab.Blog" => class_exists(\RainLab\Blog\Plugin::class),
            "RainLab.User" => class_exists(\RainLab\User\Plugin::class),
            "RainLab.Pages" => class_exists(\RainLab\Pages\Plugin::class),
            "SureSoftware.PowerSEO" => class_exists(\SureSoftware\PowerSEO\Plugin::class),
        ];
    }

    /**
     * @param $pluginName
     * @return mixed
     * @throws \Exception
     */
    public static function hasDependenciesPlugin($pluginName)
    {
        $pluginStatus = self::pluginDependenciesState();

        if ( !array_key_exists($pluginName, $pluginStatus)) {
            throw new \Exception("Invalid plugin dependencies name $pluginName given");
        } else {
            return $pluginStatus[$pluginName];
        }
    }

    public function pluginDetails()
    {
        return [
            'name' => 'FlametreeCMS',
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
        if (self::hasDependenciesPlugin('RainLab.User')) {
            $this->extendingRainLabUserPlugin();
        }


        $this->extendControllerBehaviour();
        if (self::hasDependenciesPlugin('RainLab.Pages')) {
            $this->extendPagesMenuPluginBehavior();
        }

        if(self::hasDependenciesPlugin('RainLab.Blog')) {
            $this->extendBlogCategoriesFormField();
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
            "GodSpeed\FlametreeCMS\Components\ProductCategory" => "ProductCategory",
            "GodSpeed\FlametreeCMS\Components\SpecialOrderForm" => "SpecialOrderForm",
            "GodSpeed\FlametreeCMS\Components\VideoSection" => "VideoSection",
            "GodSpeed\FlametreeCMS\Components\ImageSlider" => "ImageSlider",
            "GodSpeed\FlametreeCMS\Components\ProducerCategory" => "ProducerCategory",
            "GodSpeed\FlametreeCMS\Components\AllProducer" => "AllProducer",
            "GodSpeed\FlametreeCMS\Components\TrendingAnnouncement" => "TrendingAnnouncement",
            "GodSpeed\FlametreeCMS\Components\BusinessContact" => "BusinessContact"

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

        return [
            'godspeed.flametreecms.manage_volunteers' => [
                'tab' => 'flametreeCMS',
                'label' => 'Some permission'
            ],
        ];
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
                'label' => 'FlameTree CMS',
                'url' => Backend::url('godspeed/flametreecms/producers'),
                'icon' => 'icon-leaf',
                'permissions' => ['godspeed.flametreecms.*'],
                'order' => 500,
                "sideMenu" => [
                    "producers" => [
                        "label" => "Producers",
                        "icon" => 'icon-address-book',
                        "url" => Backend::url("godspeed/flametreecms/producers"),
                    ],
                    "specialorders" => [
                        'label' => 'Special Orders',
                        'icon' => 'icon-inbox',
                        'url' => Backend::url('godspeed/flametreecms/specialorders'),
                    ],
                    "videos" => [
                        'label' => "Videos",
                        'icon' => 'icon-film',
                        'url' => Backend::url('godspeed/flametreecms/videos'),
                    ],
                    'faqs' => [
                        "label" => "Faqs",
                        "icon" => "icon-question-circle",
                        "url" => Backend::url("godspeed/flametreecms/faqs")
                    ],
                    "imagesliders" => [
                        "label" => "Carousel/Image Slider",
                        "icon" => 'icon-picture-o',
                        "url" => Backend::url("godspeed/flametreecms/imagesliders")
                    ]
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

    public function extendingRainLabUserPlugin()
    {
        Event::listen('backend.menu.extendItems', function ($manager) {


            $manager->addSideMenuItems('RainLab.User', 'user', [
                'imports' => [
                    "label" => "Import Volunteers",
                    "icon" => 'icon-list',
                    "url" => Backend::url("rainlab/user/users/import"),
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
                'all-producer-category' => "All Producer Category"
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
        Event::listen('backend.form.extendFields', function ($widget) {
            if (!$widget->getController() instanceof \RainLab\Blog\Controllers\Categories) {
                return;
            }

            if (!$widget->model instanceof \RainLab\Blog\Models\Category) {
                return;
            }

            $widget->addFields([
                'featured_image'=> [
                'label' => 'Featued Image',
                'type' => 'mediafinder'
                ]
            ]);


        });
    }

    public function extendControllerBehaviour()
    {
        VideoModel::extend(function ($model) {
            $model->bindEvent('model.beforeSave', function () use ($model) {
                $validator = Validator::make(
                    $model->toArray(),
                    VideoModel::VALIDATION_RULES,
                    VideoModel::VALIDATION_MSG
                );

                if ($validator->fails()) {
                    throw new \ValidationException($validator);
                }
                $api = Video::make($model->toArray());
                $res = $api->get();

                if ($res['status'] === Video::OK) {
                    $data = [
                        'type' => $model->type,
                        'embed_id' => $res['embed_id'],
                        'duration' => $res['duration'],
                        'featured_image' => $res['featured_image'],
                        'title' => $res['title'],
                        'description' => $res['description'],
                    ];

                    $model->embed_id = $data['embed_id'];
                    $model->duration = $data['duration'];
                    $model->featured_image = $data['featured_image'];
                    $model->title = $data['title'];
                    $model->description = $data['description'];
                } else {
                    throw new ValidationException([
                        'embed_id' => "Invalid video id please make sure the video sources is selected correctly"
                    ]);
                }
            });
        });
    }
}
