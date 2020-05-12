<?php

namespace GodSpeed\Essentials\Traits;

trait HasBackendPermissions
{
    public function checkPermissions()
    {
        if ($this->action !== "update") {
            $this->requiredPermissions = $this->actionPermissions[$this->action] ?? [];
        }
    }

    public function update($recordId, $context = null)
    {
        //
        // Do any custom code here
        //

        // Call the ListController behavior index() method
        if (!$this->user->hasAccess($this->actionPermissions['update'])) {
            return \Response::redirectTo($this->actionUrl('preview', $recordId));
        }
        return $this->asExtension('FormController')->update($recordId, $context);
    }
}
