<?php

namespace PBS\Logout\Contracts;

interface Driver
{
    /**
     * Boot the needed driver operations.
     *
     * @return void
     */
    public function boot();

    /**
     * Return the user model.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    public function model();

    /**
     * Return the Authentication facade.
     *
     * @return \October\Rain\Support\Facade
     */
    public function facade();
}
