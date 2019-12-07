<?php

namespace PBS\Logout\Components;

use PBS\Logout\Processor;
use Cms\Classes\ComponentBase;
use PBS\Logout\Models\Settings;

class Frontend extends ComponentBase
{
    /**
     * The frontend user driver.
     * @return \PBS\Logout\Drivers\Frontend
     */
    protected $driver;

    /**
     * The component details.
     *
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name' => 'Auto-Logout',
            'description' => ''
        ];
    }

    /**
     * Once the component is running.
     *
     * @return void
     */
    public function onRun()
    {
        $this->driver = app(Processor::class)->driver('frontend');

        if ($this->driver->facade()::check()) {
            $this->addJs('https://cdn.jsdelivr.net/npm/sweetalert2@9');
            $this->addJs('/plugins/pbs/logout/resources/socket.io.js');
            $this->addJs('/plugins/pbs/logout/resources/client.js', [
                'data-url' => $this->driver->generateSocketIoUrl('frontend'),
                'data-client' => 'client',
                'data-plugin' => 'pbs.logout'
            ]);
            $this->addJs('/plugins/pbs/logout/resources/countdown.js', [
                'data-minutes' => Settings::instance()->frontend_allowed_inactivity,
                'data-countdown' => 'countdown',
                'data-plugin' => 'pbs.logout',
                'data-method' => 'onLogoutUser'
            ]);
        }
    }

    /**
     * Check whether the user should be logged out
     * or not based on his last activity.
     *
     * @return array
     */
    public function onLogoutUser()
    {
        $this->driver = app(Processor::class)->driver('frontend');

        if ($this->driver->facade()::getUser()) {
            if (strtotime($this->driver->facade()::getUser()->last_activity) < strtotime("-" . Settings::instance()->frontend_allowed_inactivity . " minutes")) {
                $this->driver->facade()::logout();
                return ['logged_out' => true];
            }
        }
        return ['logged_out' => false];
    }
}
