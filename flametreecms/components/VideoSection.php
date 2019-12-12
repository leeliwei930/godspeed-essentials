<?php namespace GodSpeed\FlametreeCMS\Components;

use Cms\Classes\CodeBase;
use Cms\Classes\ComponentBase;

use GodSpeed\FlametreeCMS\Models\Video;

use GodSpeed\FlametreeCMS\Models\Playlist;
use phpDocumentor\Reflection\DocBlock;

/**
 * Class VideoSection
 * @package GodSpeed\FlametreeCMS\Components
 */
class VideoSection extends ComponentBase
{

    /**
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'Video Section',
            'description' => 'Generate a plyr.io videos listing'
        ];
    }

    /**
     * @return array
     */
    public function defineProperties()
    {
        $properties = [
            "playlist_name" => [
                "title" => "Playlist",
                "type" => "dropdown",
                'default'     => null,
                'placeholder' => 'Select Playlist'
            ],
            "autoplay" => [
                "title" => "AutoPlay",
                "type" => "checkbox",
                'default'     => true
            ]
        ];
        $properties['playlist_name']['options'] = $this->getPlaylistOptions();

        return $properties;
    }



    /**
     * @return mixed;
     * The result might be different first if condition when valid return an object from the instance
     * of video playlist with eager loading of videos relationships
     *
     * The else block return a collection of Video Object that the video playlist id is null  which
     * indicate as "uncategorized" playlist
     *
     */
    public function getAllVideos()
    {
        $playlistName = $this->property('playlist_name');

        if (!is_null($playlistName)) {
            $videoPlaylist = Playlist::with(['videos'])->find($playlistName);
        } else {
            $videos = Video::where('video_playlist_id', null)->get();
            $videoPlaylist['videos'] = $videos;
            $videoPlaylist['name'] = "Uncategorized";
        }

        return $videoPlaylist;
    }


    public function getPlaylistOptions()
    {
        return Playlist::pluck('name' , 'id')->toArray();
    }
}
