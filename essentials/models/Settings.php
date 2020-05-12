<?php namespace GodSpeed\Essentials\Models;

use Model;

class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'godspeed_essentials_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';

    public $casts = [
        'lazyload_image_blur_rate' => 'int',
        'lazyload_image_quality' => 'int'
    ];

    public function getLazyLoadImageQualityOptions()
    {
        $options = [];

        for($count = 5; $count <= 100; $count+=5){
            $options[$count] = $count."%";
        }

        return $options;
    }
    public function getLazyLoadImageBlurRateOptions()
    {
        $options = [];

        for($count = 5; $count <= 15; $count+=5){
            $options[$count] = $count;
        }

        return $options;
    }

    public function getLazyLoadDefaultImageExtensionOptions()
    {
        return [
            'auto' => "Auto",
            'jpeg' => "JPEG",
            'jpg' => "PNG",
            "gif" => "GIF"
        ];
    }
}
