<?php

namespace GodSpeed\Essentials\Utils\Providers;

use Faker\Generator;

abstract class VideoProvider extends \Faker\Provider\Base
{

    abstract public function getVideoEmbedId($genre);
    abstract public function getVideoSource();
}
