<?php
/**
 * This file is part of NAS-Broker projects
 *
 * @package NAS.QWorker
 * @author Dmytro Kulyk <lnkvisitor.ts@gmail.com>
 */

namespace common\qworker\operations;

use common\models\Account\TradeAccount;
use common\models\Export\Event;
use common\models\User;
use common\qworker\Exception\Ignore;

/**
 * Class AbstractOperation
 */
abstract class AbstractOperation
{
    /**
     * Operation dispatcher
     * @param string $operation
     * @param mixed $data
     * @param Event $event
     * @return mixed|null
     */
    public static function dispatch($operation, $data, Event $event)
    {
        $operationClass = new \ReflectionClass(__NAMESPACE__ . '\\' . $operation);
        if ($operationClass->isSubclassOf(self::class)) {
            $data = $operationClass->getMethod('prepare')->invoke($operationClass->newInstance(), $data, $event);

            return $data;
        }
        \Yii::error("Class {$operationClass->name} must be subclass of " . __CLASS__, 'worker');

        return null;
    }

    /**
     * Prepare operation data
     * @param mixed $data
     * @param Event $event
     * @return array|null
     */
    abstract public function prepare($data, Event $event);

    /**
     * Get allowed operations
     *
     * @return string[]
     */
    static public function operations()
    {
        $result = [];
        foreach (new \DirectoryIterator(__DIR__) as $file) {
            if ($file->isDot() || $file->isDir()) {
                continue;
            }
            $name = $file->getBasename('.php');
            if (class_exists(__NAMESPACE__ . '\\' . $name)) {
                $class = new \ReflectionClass(__NAMESPACE__ . '\\' . $name);
                if ($class->isAbstract()) {
                    continue;
                }
                $class->getName();
                $result[] = $class->getShortName();
            }
        }

        return $result;
    }
}
