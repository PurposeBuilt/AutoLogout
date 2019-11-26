<?php

namespace PBS\Logout;

use Backend;
use System\Classes\PluginBase;
use PBS\Logout\Models\Settings;

/**
 * Logout Plugin Information File
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
            'name'        => 'Logout',
            'description' => 'Automatically log out users when they leave the website!',
            'author'      => 'PBS',
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
        $this->registerConsoleCommand('pbs.setup', 'PBS\Logout\Console\SetUp');
        $this->registerConsoleCommand('pbs.run', 'PBS\Logout\Console\Run');

        /**
         * Registering Processor Drivers Manager.
         */
        $this->app->singleton(Processor::class, function () {
            return new Processor($this->app);
        });
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        app(Processor::class)->driver('backend')->boot()->settings();

        // If Rainlab User Plugin is installed, then we should call
        // its processor so its settings fields will be displayed.
        if (class_exists('Rainlab\User\Models\User')) {
            app(Processor::class)->driver('frontend')->boot()->settings();
        }
    }

    /**
     * Register the settings page of the plugin.
     *
     * @return array
     */
    public function registerSettings()
    {
        return [
            'logout' => [
                'label'       => 'Logout',
                'description' => 'Manage the settings of the auto-logout plugin.',
                'category'    => 'Users',
                'icon'        => 'icon-globe',
                'class'       => \PBS\Logout\Models\Settings::class,
                'order'       => 500,
            ]
        ];
    }

    /**
     * Registering the needed components.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'PBS\Logout\Components\Frontend' => 'autologout'
        ];
    }
}
