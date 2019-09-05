<?php namespace GodSpeed\InstagramGallery;

use Backend;
use System\Classes\PluginBase;

/**
 * InstagramGallery Plugin Information File
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
            'name'        => 'InstagramGallery',
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
        return []; // Remove this line to activate

        return [
            'GodSpeed\InstagramGallery\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'godspeed.instagramgallery.some_permission' => [
                'tab' => 'InstagramGallery',
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
        return []; // Remove this line to activate

        return [
            'instagramgallery' => [
                'label'       => 'InstagramGallery',
                'url'         => Backend::url('godspeed/instagramgallery/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['godspeed.instagramgallery.*'],
                'order'       => 500,
            ],
        ];
    }
}
