<?php
namespace GodSpeed\FlametreeCMS\Utils\VideoMeta;

use FFMpeg\FFProbe;
use GodSpeed\FlametreeCMS\Contracts\VideoMetaAPI;
use GodSpeed\FlametreeCMS\Models\Video as VideoModel;
use Illuminate\Support\Facades\Log;

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

    protected $request;
    public function __construct($request)
    {
        $this->request = $request;
    }

    public function get()
    {
        $data = [
            "status" => self::OK,
            "duration" => 0,
            "featured_image" =>  $this->request['featured_image']['for']['video'],
            "title" =>  $this->request['title'],
            "description" => $this->request['description'],
            "embed_id" => $this->request['embed_id']['for']['video']
        ];

        return $data;
    }

    public function getConfig($key)
    {
        return [];
    }

    final public static function make($request)
    {

        $classname = "\GodSpeed\FlametreeCMS\Utils\VideoMeta";
        $type =  $request['type'];
        $classname .= "\\".ucfirst(($request['type']));
        return new $classname($request);
    }
}
