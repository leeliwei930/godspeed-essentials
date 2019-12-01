<?php namespace GodSpeed\FlametreeCMS\Models;

use Model;

class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'flametree_cms_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';
}
