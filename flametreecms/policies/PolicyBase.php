<?php

namespace GodSpeed\FlametreeCMS\Policies;

abstract class PolicyBase
{

    protected $subjectModel;
    protected $resourceModel;
    protected $appRunningEnv;
    protected $conditionClosure;




    abstract public static function check($resourceModel);
    abstract public static function make($resourceModel);
    abstract  public function getRuleConditionClosure();

}
