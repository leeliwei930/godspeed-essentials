<?php

namespace GodSpeed\FlametreeCMS\Utils\Providers;

use Faker\Generator;

class YoutubeVideoProvider extends VideoProvider
{
    public function __construct(Generator $generator)
    {
        parent::__construct($generator);
    }

    const sources = [

        "flametree" => [
            "qEdLc4klnu4",
            "m0ZmTEDn-j4",
            "vUB6qLGZAqc"
        ]
    ];


    public function getVideoEmbedId($genre = "flametree")
    {
        return collect(array_random(self::sources[$genre], 1))->first();
    }

    public function getVideoSource()
    {
        return 'youtube';
    }
}
