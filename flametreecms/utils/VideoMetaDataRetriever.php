<?php

namespace GodSpeed\FlametreeCMS\Utils;

use Carbon\Carbon;
use GodSpeed\FlametreeCMS\Models\Settings;
use October\Rain\Network\Http;

/**
 * Class VideoMetaDataRetriever
 * @package GodSpeed\FlametreeCMS\Utils
 */
class VideoMetaDataRetriever
{
    /**
     * The status of the meta data request
     */
    const OK = 1;
    /**
     *
     */
    const NOT_FOUND = 2;


    /**
     * youtube api endpoint configuration
     * @var array
     */
    public static $youtubeAPIConfig = [
        'api_base' => "https://www.googleapis.com/youtube/v3/videos",
        'thumbnail_base' => "https://i.ytimg.com/vi/"
    ];

    /**
     * Vimeo API Endpoint Configuration
     * @var array
     */
    public static $vimeoAPIConfig = [
        'api_base' => "https://vimeo.com/api/oembed.json"
    ];

    /**
     * Request meta data for associated youtube video ID / embed ID
     * API Key is required and can be set through backend settings
     * @param $embedID
     * @return array
     * @throws \Exception
     */
    public static function youtubeVideoData($embedID)
    {
        $youtubeRes = Http::get(self::$youtubeAPIConfig['api_base'] . '?' . http_build_query([
            'part' => 'contentDetails,snippet',
            'id' => $embedID,
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
                "featured_image" => self::$youtubeAPIConfig['thumbnail_base'] . $embedID . "/sddefault.jpg",
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

    /**
     * Request meta data for associated vimeo video ID / embed ID
     * @param $embedID
     * @return array
     */
    public static function vimeoVideoData($embedID)
    {
        $vimeoResponse = Http::get(self::$vimeoAPIConfig['api_base'] . '?' . http_build_query([
            'url' => "https://vimeo.com/".$embedID
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
}
