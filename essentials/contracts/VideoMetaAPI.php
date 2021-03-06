<?php

namespace GodSpeed\Essentials\Contracts;

interface VideoMetaAPI {

    /**
     * Set configuration for API
     * @return ["key" => "value"]
     */
    public function getConfig($key);
    public function get();
}
