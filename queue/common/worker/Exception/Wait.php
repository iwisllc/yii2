<?php

namespace common\worker\Exception;

use common\worker\Exception;

/**
 * Class Wait
 */
class Wait extends Exception
{
    /**
     * Restart job without pause
     * @var bool
     */
    public $fast = false;
}