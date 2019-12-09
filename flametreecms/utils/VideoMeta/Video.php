<?php
namespace GodSpeed\FlametreeCMS\Utils\VideoMeta;

use GodSpeed\FlametreeCMS\Contracts\VideoMetaAPI;

class Video implements VideoMetaAPI
{
      /**
     * The status of the meta data request
     */
    const OK = 1;
    /**
     *
     */
    const NOT_FOUND = 2;


    public function get($resource)
    {
        return $resource;
    }

    public function getConfig($key)
    {
        return [];
    }

    final public static function make($name)
    {
        $classname = "\GodSpeed\FlametreeCMS\Utils\VideoMeta";
        $classname .= "\\".ucfirst($name);
        return new $classname;
    }
}
