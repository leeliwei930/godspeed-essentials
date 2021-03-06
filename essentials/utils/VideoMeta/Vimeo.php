<?php
namespace GodSpeed\Essentials\Utils\VideoMeta;

use October\Rain\Network\Http;

class Vimeo extends Video
{
    public $name = "vimeo";

    public function __construct($model)
    {
        parent::__construct($model);
    }

    public function get()
    {

        $vimeoResponse = Http::get($this->getConfig('api_base') . '?' . http_build_query([
            'url' => "https://vimeo.com/".$this->request['embed_id']
        ]))->send();

        $response = json_decode($vimeoResponse, true);

        if ( !is_null($response)) {
            $data = [
                "status" => self::OK,
                "duration" => $response['duration'],
                "featured_image" => $response['thumbnail_url'],
                "title" => $response["title"],
                "description" => $response['description'],
                "embed_id" => $response['video_id']
            ];
        } else {
            $data = [
                "status" => self::NOT_FOUND
            ];
        }

        return $data;
    }

    public function getConfig($key)
    {

        $config = [
            'api_base' => "https://vimeo.com/api/oembed.json"
        ];
        return $config[$key];
    }
}
