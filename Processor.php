<?php

namespace PBS\Logout;

use Illuminate\Support\Manager;
use PBS\Logout\Drivers\Backend;
use PBS\Logout\Drivers\Frontend;

class Processor extends Manager
{
    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return 'backend';
    }

    /**
     * Create a new Backend driver instance.
     *
     * @return PBS\Logout\Drivers\Backend
     */
    public function createBackendDriver()
    {
        return new Backend($this->app);
    }

    /**
     * Create a new Frontend driver instance.
     *
     * @return PBS\Logout\Drivers\Frontend
     */
    public function createFrontendDriver()
    {
        return new Frontend($this->app);
    }
}
