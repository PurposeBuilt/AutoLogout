<?php

namespace PBS\Logout;

use System\Classes\PluginBase;

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
         * Registering Mask Facade.
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
        app(Processor::class)->driver('backend')->boot();
    }
}
