<?php namespace GodSpeed\FlametreeCMS\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use GodSpeed\FlametreeCMS\Plugin;
use GodSpeed\FlametreeCMS\Utils\VideoMetaDataRetriever;
use Lang;
/**
 * Video Back-end Controller
 */
class Video extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('GodSpeed.FlametreeCMS', 'flametreecms', 'video');
    }

    public function create_onSave($context = null)
    {
        $request = request()->all()['Video'];
        trace_log($request);
        if ($request['type'] === 'youtube') {
            $videoMetaData = VideoMetaDataRetriever::youtubeVideoData($request['embed_id']);
        } else {
            $videoMetaData = VideoMetaDataRetriever::vimeoVideoData($request['embed_id']);
        }

        if ($videoMetaData['status'] === VideoMetaDataRetriever::OK) {
            $data = [
                'type' => $request['type'],
                'embed_id' => $videoMetaData['embed_id'],
                'duration' => $videoMetaData['duration'],
                'featured_image' => $videoMetaData['featured_image'],
                'title' => $videoMetaData['title'],
                'description' => $videoMetaData['description'],
            ];
            // if the user select a playlist
            if (!empty($request['video_playlist'])) {
                $playlist = \GodSpeed\FlametreeCMS\Models\VideoPlaylist::findOrFail($request['video_playlist']);

                $res = $playlist->addVideo($data);
            } else {
                $res = \GodSpeed\FlametreeCMS\Models\Video::create($data);
            }

            if (!is_null($res)) {
                \Flash::success($this->getLang("{$this->context}[flashSave]", 'backend::lang.form.create_success'));

            } else {
                 \Flash::error("There is a problem while create this video");
            }
        } else {
             \Flash::error("Invalid video id please make sure the video sources is selected correctly");
        }
        return redirect(\Backend::url('/godspeed/flametreecms/video'));
    }

    public function update_onSave($recordId = null, $context = null)
    {
        $video  = \GodSpeed\FlametreeCMS\Models\Video::findOrFail($recordId);
        $request = request()->all()['Video'];
        trace_log($request);
        if ($request['type'] === 'youtube') {
            $videoMetaData = VideoMetaDataRetriever::youtubeVideoData($request['embed_id']);
        } else {
            $videoMetaData = VideoMetaDataRetriever::vimeoVideoData($request['embed_id']);
        }

        if ($videoMetaData['status'] === VideoMetaDataRetriever::OK) {
            $data = [
                'type' => $request['type'],
                'embed_id' => $videoMetaData['embed_id'],
                'duration' => $videoMetaData['duration'],
                'featured_image' => $videoMetaData['featured_image'],
                'title' => $videoMetaData['title'],
                'description' => $videoMetaData['description'],
            ];
            // if the user select a playlist
            $success = $video->update($data);

            if (!empty($request['video_playlist'])) {
                $video->video_playlist_id = $request['video_playlist'];
                $success = $video->save();
            }

            if (!is_null($success)) {
                \Flash::success($this->getLang("{$this->context}[flashSave]", 'backend::lang.form.update_success'));
            } else {
                \Flash::error("There is a problem while create this video");
            }
        } else {
            \Flash::error("Invalid video id please make sure the video sources is selected correctly");
        }
        return redirect(\Backend::url('/godspeed/flametreecms/video/preview/' . $video->id));
    }

    protected function getLang($name, $default = null, $extras = [])
    {
        $name = $this->getConfig($name, $default);
        $vars = [
            'name' => Lang::get($this->getConfig('name', 'backend::lang.model.name'))
        ];
        $vars = array_merge($vars, $extras);
        return Lang::get($name, $vars);
    }
}
