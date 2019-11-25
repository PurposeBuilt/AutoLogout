<?php

namespace PBS\Logout\Drivers;

use App;
use Event;
use Backend\Models\User;
use Backend\Facades\BackendAuth;
use PBS\Logout\Contracts\Driver;

class Backend extends BaseDriver implements Driver
{
    /**
     * Return the user model.
     *
     * @return \Backend\Models\User
     */
    public function model()
    {
        return User::class;
    }

    /**
     * Return the Authentication facade.
     *
     * @return \Backend\Facades\BackendAuth
     */
    public function facade()
    {
        return BackendAuth::class;
    }

    /**
     * Boot the needed driver operations.
     *
     * @return void
     */
    public function boot()
    {
        // Check if we are currently in backend module.
        if (!App::runningInBackend()) {
            return;
        }

        // Listen for `backend.page.beforeDisplay` event and inject js to current controller instance.
        Event::listen('backend.page.beforeDisplay', function ($controller, $action, $params) {
            if ($this->facade()::check()) {
                $controller->addJs('/plugins/pbs/logout/resources/socket.io.js');
                $controller->addJs('/plugins/pbs/logout/drivers/backend/client.js', [
                    'data-url' => $this->generateSocketIoUrl('backend'),
                    'data-plugin' => 'pbs.logout'
                ]);
            }
        });
    }
}
