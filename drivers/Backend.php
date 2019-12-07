<?php

namespace PBS\Logout\Drivers;

use App;
use Event;
use Carbon\Carbon;
use Backend\Models\User;
use PBS\Logout\Models\Settings;
use Backend\Classes\Controller;
use Backend\Facades\BackendAuth;
use PBS\Logout\Contracts\Driver;
use PBS\Logout\Drivers\Backend\Middleware;

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
     * @return self
     */
    public function boot()
    {
        if (!Settings::instance()->backend_enabled) {
            return $this;
        }

        // Check if we are currently in backend module.
        if (!App::runningInBackend()) {
            return $this;
        }

        // Listen for `backend.page.beforeDisplay` event and inject js to current controller instance.
        Event::listen('backend.page.beforeDisplay', function ($controller, $action, $params) {
            if ($this->facade()::check()) {
                $controller->addJs('https://cdn.jsdelivr.net/npm/sweetalert2@9');
                $controller->addJs('/plugins/pbs/logout/resources/socket.io.js');
                $controller->addJs('/plugins/pbs/logout/resources/client.js', [
                    'data-url' => $this->generateSocketIoUrl('backend'),
                    'data-client' => 'client',
                    'data-plugin' => 'pbs.logout'
                ]);
                $controller->addJs('/plugins/pbs/logout/resources/countdown.js', [
                    'data-minutes' => Settings::instance()->backend_allowed_inactivity,
                    'data-countdown' => 'countdown',
                    'data-plugin' => 'pbs.logout',
                    'data-method' => 'onLogout',
                    'data-custom-class' => Settings::instance()->backend_popup_custom_class
                ]);
            }
        });

        // The middleware that's responsible for updating user's activity.
        // We're updating the last activity of the user with each request.
        $this->app['Illuminate\Contracts\Http\Kernel']->pushMiddleware(Middleware::class);

        // Add log out method to all backend controllers.
        // In that method we check the last activity,
        // if it's more than the specified minutes,
        // we'll log out the user.
        Controller::extend(function ($controller) {
            $controller->addDynamicMethod('onLogout', function () {
                if ($this->facade()::getUser()) {
                    if (strtotime($this->facade()::getUser()->last_activity) < strtotime("-" . Settings::instance()->backend_allowed_inactivity . " minutes")) {
                        $this->facade()::logout();
                        return ['logged_out' => true];
                    }
                }
                return ['logged_out' => false];
            });
        });

        // Extending the user model to add a method that
        // updates the last activity so the middleware
        // can use it every request.
        $this->model()::extend(function ($model) {
            $model->addDynamicMethod('updateActivity', function () use ($model) {
                $model->last_activity = Carbon::now();
                $model->save();
            });
        });

        return $this;
    }

    /**
     * Add the settings for this driver.
     *
     * @return void
     */
    public function settings()
    {
        Event::listen('backend.form.extendFields', function ($widget) {
            if (!$widget->model instanceof Settings) {
                return;
            }

            $widget->addFields([
                'backend_enabled' => [
                    'label' => 'Enable Backend Auto-Logout',
                    'comment' => 'This option will auto-logout admins who leave the dashboard entirely.',
                    'type' => 'checkbox',
                    'span' => 'left',
                    'default' => false,
                ],
                'backend_allowed_inactivity' => [
                    'label' => 'Admins Mins of inactivity',
                    'comment' => 'The number of minutes of inactivity allowed before the admin gets logged out. Make it zero if you don\'nt want to logout the user after amount of inactivity time.',
                    'span' => 'right',
                    'default' => 0,
                    'trigger' => [
                        'action' => 'show',
                        'field' => 'backend_enabled',
                        'condition' => 'checked'
                    ]
                ],
                'backend_popup_custom_class' => [
                    'label' => 'Backend Popup Custom Class',
                    'comment' => 'If you want to add a custom class for the warning modal in backend dashboard.',
                    'span' => 'right',
                    'default' => 0,
                    'trigger' => [
                        'action' => 'show',
                        'field' => 'backend_enabled',
                        'condition' => 'checked'
                    ]
                ],
            ]);
        });
    }
}
