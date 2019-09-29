<?php namespace GodSpeed\FlametreeCMS;

use Backend;
use System\Classes\PluginBase;

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
            'godspeed.flametreecms.some_permission' => [
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
                ]
            ],
        ];
    }

    public function registerMailTemplates()
    {
        return [
            'godspeed.flametreecms::mail.templates.receiver-template',
             'godspeed.flametreecms::mail.templates.sender-template'
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

}
