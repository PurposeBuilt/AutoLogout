<?php

namespace PBS\Logout\Models;

use Model;

class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'pbs_logout_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';
}
