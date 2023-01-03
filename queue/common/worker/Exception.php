<?php

namespace common\worker;

/**
 * Class Exception
 */
class Exception extends \Exception
{
    /**
     * @inheritdoc
     */
    public function __construct($message = '', $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        \Yii::error(__CLASS__ . ': ' . $message, 'worker');
    }
}