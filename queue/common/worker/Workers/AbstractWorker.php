<?php

namespace common\worker\Workers;

use common\models\Job;
use common\worker\Exception as QException;

/**
 * Class AbstractWorker
 */
abstract class AbstractWorker
{

    /**
     * Split worker methods in classes
     * @var bool
     */
    static protected $methodAsClass = false;

    /**
     * Dispatch worker jobs
     *
     * @param string $worker Worker class name
     * @param string $method Worker method name
     * @param array $data Job data
     * @param Job $job JobID
     * @return int
     * @throws \Exception
     * @throws \common\worker\Exception
     */
    public static function dispatch($worker, $method, $data, Job $job)
    {
        /**
         * @var AbstractWorker $class
         */
        $class = __NAMESPACE__ . '\\' . $worker;
        if ($class::$methodAsClass) {
            $class .= '\\' . $method;
        }
        $workerClass = new \ReflectionClass($class);

        if ($workerClass->isSubclassOf(self::class)) {

            //$config = array_key_exists($worker, \Yii::$app->params['workers']) ? \Yii::$app->params['workers'][$worker] : [];
            $config = [];

            return $workerClass->getMethod('run')->invoke($workerClass->newInstance($config), $data, $job);
        }
        throw new QException\Fail('Class ' . $workerClass->name . ' must be subclass of ' . static::class);
    }

    /**
     * Create worker
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->_config = $config;
    }

    /**
     * Get config value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function config($key, $default = null)
    {
        return array_key_exists($key, $this->_config) ? $this->_config[$key] : $default;
    }

    /**
     * Run worker job
     * @param array $data
     * @param Job $job
     * @return int Result code
     */
    abstract public function run($data, Job $job);
}