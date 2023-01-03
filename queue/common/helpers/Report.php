<?php

namespace common\helpers;

/**
 * Trait Report
 */
trait Report
{
    /**
     * Get report string
     *
     * @param int $maxLevel
     * @param int $level
     *
     * @return string
     */
    public function getReport($maxLevel = -1, $level = 0)
    {
        return $this->prepareReport($this, $this->getReportFields(), $maxLevel, $level);
    }

    /**
     * Prepare report string
     *
     * @param mixed $object
     * @param array        $fields
     * @param int          $maxLevel
     * @param int          $level
     *
     * @return string
     */
    private function prepareReport($object, array $fields = [], $maxLevel, $level = 0)
    {
        if ($maxLevel >= 0 && $maxLevel < $level) {
            return str_repeat('    ', $level).'Increase max level for report.'.PHP_EOL;
        }
        $data = [];
        $offset = 0;
        foreach ($fields as $label => $field) {

            if ($field instanceof \Closure) {
                $value = $field();
            } else {
                $value = is_array($object) ? $object[$field] : $object->$field;
            }

            if ($value === null) {
                continue;
            }

            if (is_array($value)) {
                $keys = array_keys($value);
                $data[$label] = 'array'.PHP_EOL.
                    $this->prepareReport($value, array_combine($keys, $keys), $maxLevel, $level + 1);
                continue;
            }

            if (is_object($value) && array_key_exists(Report::class, class_uses($value))) {
                /* @var Report $value */
                $data[$label] = get_class($value).PHP_EOL.$value->getReport($maxLevel, $level + 1);
            } else {
                if ($value instanceof \Exception) {
                    $data[$label] = get_class($value).PHP_EOL.$this->prepareReport(
                            $value, [
                            'Code'     => function () use ($value) {
                                return $value->getCode();
                            },
                            'Message'  => function () use ($value) {
                                return $value->getMessage();
                            },
                            'File'     => function () use ($value) {
                                return $value->getFile();
                            },
                            'Line'     => function () use ($value) {
                                return $value->getLine();
                            },
                            'Previous' => function () use ($value) {
                                return $value->getPrevious();
                            },
                            'Stack' => function () use ($value) {
                                return $value->getTraceAsString();
                            },
                        ], $maxLevel, $level + 1
                        );
                } else {
                    $data[$label] = $value;
                }
            }

            $offset = max($offset, strlen($label));
        }

        $result = '';
        array_walk(
            $data, function ($value, $label, $prefix) use (&$result, $offset) {
            $result .= $prefix.str_pad($label.':', $offset + 2, ' ', STR_PAD_RIGHT).serialize($value).PHP_EOL;
        }, str_repeat('    ', $level)
        );

        return $result;
    }

    /**
     * Return report fields
     *
     * ```
     *  return [
     *      'Id' => 'id',
     *      'Amount' => function() {
     *          return number_format($this->amount, 2);
     *      }
     *  ];
     * ```
     *
     * @return array
     */
    protected function getReportFields()
    {
        return [];
    }
}