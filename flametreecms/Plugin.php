<?php namespace GodSpeed\FlametreeCMS;

use Backend;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use RainLab\User\Models\User;
use System\Classes\PluginBase;
use RainLab\User\Controllers\Users as RainLabUsersController;

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
    public $require = ['RainLab.User'];

    public function pluginDetails()
    {
        return [
            'name'        => 'flametreeCMS',
            'description' => 'No description provided yet...',
            'author'      => 'GodSpeed',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        // extend users importer
        $this->extendingRainLabUserPlugin();
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
            "GodSpeed\FlametreeCMS\Components\SpecialOrderForm" => "SpecialOrderForm"
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
                'label'       => 'FlameTree CMS',
                'url'         => Backend::url('godspeed/flametreecms/products'),
                'icon'        => 'icon-leaf',
                'permissions' => ['godspeed.flametreecms.*'],
                'order'       => 500,
                "sideMenu" => [
                    "products" => [
                        "label" => "Products",
                        "icon" => 'icon-list',
                        "url" => Backend::url("godspeed/flametreecms/products"),
                    ],
                    "productcategories" => [
                        'label' => 'Product Category',
                        'icon'  => 'icon-cube',
                        'url'   => Backend::url('godspeed/flametreecms/productcategories'),
                    ],
                    "specialorders" => [
                        'label' => 'Special Orders',
                        'icon'  => 'icon-inbox',
                        'url'   => Backend::url('godspeed/flametreecms/specialorders'),
                    ],
                    "video" => [
                        'label' => "Videos",
                        'icon' => 'icon-film',
                        'url'   => Backend::url('godspeed/flametreecms/video'),
                    ],
                    "videoplaylist" => [
                        "label" => "Video Playlist",
                        "icon" => "icon-list",
                        "url" => Backend::url("godspeed/flametreecms/videoplaylist")
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

    public function extendingRainLabUserPlugin()
    {
        Event::listen('backend.menu.extendItems', function ($manager) {


            $manager->addSideMenuItems('RainLab.User', 'user', [
                'Import volunteers' => [
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
}
