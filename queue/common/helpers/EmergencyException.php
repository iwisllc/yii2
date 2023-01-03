<?php

namespace common\helpers;

use Exception;
use yii\swiftmailer\Message;

class EmergencyException extends Exception {
    use Report;

    /**
     * Exception data
     *
     * @var mixed
     */
    protected $data;

    /**
     * Gets the exception data
     *
     * @return mixed
     */
    public function getData() {
        return $this->data;
    }

    /**
     * EmergencyException constructor.
     *
     * @param string                 $message
     * @param int                    $code
     * @param  \Throwable|\Exception $previous
     * @param mixed                  $data
     */
    public function __construct($message = "", $code = 0, $previous = null, $data = null) {
        parent::__construct($message, $code, $previous);
        $this->data = $data;

        $report = 'Emergency at ' . date(DATE_ATOM) . PHP_EOL . $this->getReport(3);

        $mail = \Yii::$app->mailer->compose()
            ->setFrom(\Yii::$app->params['adminEmail'])
            ->setTo(\Yii::$app->params['adminEmail'])
            ->setSubject($this->getMessage())
            ->setHtmlBody('<pre>' . $report . '</pre>');

        try{
            $mail->send();
        }Catch(Exception $e){
            //
        }
    }

    protected function getReportFields() {
        return [
            'Code'     => 'code',
            'Message'  => 'message',
            'File'     => 'file',
            'Line'     => 'line',
            'Previous' => function () {
                return $this->getPrevious();
            },
            'Data'     => 'data',
            'Stack'    => function () {
                return $this->getTraceAsString();
            },
        ];
    }
}