<?php

namespace GodSpeed\Essentials\Utils\Providers;

use Faker\Generator;

class VimeoVideoProvider extends VideoProvider
{
    const sources = [
        "rocket" => [
            "sZlzYzyREAI",
            "ujX6CuRELFE",
            "Tk338VXcb24",
            "4jEz03Z8azc"
        ],
        "science" => [
            "v3y8AIEX_dU",
            "NMo3nZHVrZ4",
            "WPPPFqsECz0",
            "5iPH-br_eJQ",
            "JhHMJCUmq28"
        ]
    ];


    public function __construct(Generator $generator)
    {
        parent::__construct($generator);
    }

    public function getVideoEmbedId($genre = "science")
    {
        return collect(array_rand(self::sources[$genre], 1))->first();
    }

    public function getVideoSource()
    {
        return 'vimeo';
    }
}
