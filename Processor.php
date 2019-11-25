<?php

namespace PBS\Logout;

use Illuminate\Support\Manager;
use PBS\Logout\Drivers\Backend;

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
        return new Backend;
    }
}