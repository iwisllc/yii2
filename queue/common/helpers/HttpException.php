<?php
/**
 * Created by PhpStorm.
 * User: stas
 * Date: 05.01.2018
 * Time: 12:52
 */

namespace common\helpers;


class HttpException extends \yii\web\HttpException
{

    public $rawData;

    /**
     * Constructor.
     *
     * @param int        $status   HTTP status code, such as 404, 500, etc.
     * @param string     $message  error message
     * @param int        $code     error code
     * @param \Exception $previous The previous exception used for the exception chaining.
     * @param null       $rawData
     */
    public function __construct($status, $message = null, $code = 0, \Exception $previous = null, $rawData = null)
    {
        $this->statusCode = $status;
        $this->rawData = $rawData;
        parent::__construct($message, $code, $previous);
    }

    public function getData(){
        return $this->rawData;
    }

}