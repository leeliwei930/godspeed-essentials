<?php
namespace GodSpeed\FlametreeCMS\Utils\VideoMeta;


use October\Rain\Network\Http;
use GodSpeed\FlametreeCMS\Models\Settings;
use Carbon\Carbon;


class Youtube extends Video

{
    public $name = "youtube";

    public function get($resource)
    {
        $youtubeRes = Http::get($this->getConfig('api_base') . '?' . http_build_query([
            'part' => 'contentDetails,snippet',
            'id' => $resource,
            'key' => Settings::get('youtube_data_api_key')
        ]))->send();
        $response = json_decode($youtubeRes, true);

        if (!is_null($response) && count($response["items"]) > 0) {
            // parsing duration from YouTube response with ISO8601 format
            $dtInterval = new \DateInterval($response["items"][0]['contentDetails']['duration']);
            //calculate the duration
            $start = Carbon::now();
            $end = Carbon::now()->addYears($dtInterval->y)
                    ->addMonths($dtInterval->m)
                    ->addDays($dtInterval->d)
                    ->addHours($dtInterval->h)
                    ->addMinutes($dtInterval->i)
                    ->addSeconds($dtInterval->s);
            $seconds = $end->diffInSeconds($start);
            $data = [
                "status" => self::OK,
                "duration" => $seconds,
                "featured_image" => $this->getConfig('thumbnail_base') . $resource . "/sddefault.jpg",
                "title" => $response["items"][0]['snippet']['title'],
                "description" => $response['items'][0]['snippet']['description'],
                "embed_id" => $response['items'][0]['id']
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
            'api_base' => "https://www.googleapis.com/youtube/v3/videos",
            'thumbnail_base' => "https://i.ytimg.com/vi/"
        ];

        return $config[$key];
    }
}
