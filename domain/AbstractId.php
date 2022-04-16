<?php

namespace app\domain;

use SaasOvation\Common\AssertionConcern;

class AbstractId implements Identity
{
    /**
     * @var string
     */
    protected $id;

    public function id()
    {
        return $this->id;
    }

    public function equals($anObject)
    {
        $equalObjects = false;

        if (null !== $anObject && get_class($this) === get_class($anObject)) {
            $equalObjects = $this->id() === $anObject->id();
        }

        return $equalObjects;
    }

}
