<?php namespace GodSpeed\PageGuard;

use Backend;
use System\Classes\PluginBase;

/**
 * PageGuard Plugin Information File
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
            'name'        => 'PageGuard',
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
            'GodSpeed\PageGuard\Components\MyComponent' => 'myComponent',
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
            'godspeed.pageguard.some_permission' => [
                'tab' => 'PageGuard',
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
            'pageguard' => [
                'label'       => 'PageGuard',
                'url'         => Backend::url('godspeed/pageguard/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['godspeed.pageguard.*'],
                'order'       => 500,
            ],
        ];
    }
}
