<?php

namespace GodSpeed\Essentials\Contracts;

interface ShouldFlashAlert
{
    public function flash($onSuccess, $onError);
}
