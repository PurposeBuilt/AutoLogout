<?php

namespace PBS\Logout\Drivers;

class BaseDriver
{
    /**
     * Logout the user.
     *
     * @param int $id
     *
     * @return void
     */
    public function logout($id)
    {
        $userToLogout = $this->model()::find($id);
        $this->facade()::setUser($userToLogout);
        $this->facade()::logout();
    }

    /**
     * Generate Socket IO Url.
     *
     * @return string
     */
    public function generateSocketIoUrl($driver)
    {
        return config('app.url') . ':4000' . '?driver=' . strtolower($driver) . '&&user_id=' . $this->facade()::getUser()->id;
    }
}
