<?php

namespace PBS\Logout\Components;

use PBS\Logout\Processor;
use Cms\Classes\ComponentBase;

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
            $this->addJs('/plugins/pbs/logout/resources/socket.io.js');
            $this->addJs('/plugins/pbs/logout/drivers/frontend/assets/client.js', [
                'data-url' => $this->driver->generateSocketIoUrl('frontend'),
                'data-client' => 'client',
                'data-plugin' => 'pbs.logout'
            ]);
        }
    }
}
