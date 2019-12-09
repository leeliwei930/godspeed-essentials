<?php

namespace GodSpeed\FlametreeCMS\Contracts;

interface ShouldFlashAlert
{
    public function flash($onSuccess, $onError);
}
