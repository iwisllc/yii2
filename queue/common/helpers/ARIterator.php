<?php

namespace common\helpers;

/**
 * Class ARIterator
 */
class ARIterator implements \Iterator
{
    /**
     * Iterator timeout
     *
     * @var int
     */
    private $_timeout = 0;

    /**
     * Class for fetch next record
     *
     * @var string
     */
    private $_class;

    /**
     * Fetch options
     *
     * @var array
     */
    protected $_options = [];
    /**
     * Current item
     *
     * @var mixed
     */
    private $_current;

    /**
     * Star time in seconds
     *
     * @var int
     */
    private $_start;

    /**
     * Terminate loop
     *
     * @var bool
     */
    private $_terminate = false;

    /**
     * Create ARIterator instance
     *
     * @param string $class   Class name
     * @param int    $timeout optional
     * @param array  $options
     */
    public function __construct($class, $timeout = 0, array $options = [])
    {
        $this->_options = $options;
        $this->_class = $class;
        $this->_timeout = (int)$timeout;
        if (function_exists('pcntl_signal')) {
            pcntl_signal(SIGINT, [$this, 'terminate']);
        }
    }

    /**
     * Get current record
     *
     * @return mixed
     */
    public function current()
    {
        return $this->_current;
    }

    /**
     * Fetch next record
     */
    public function next()
    {
        $this->_current = null;
        while (!$this->_terminate && ($this->_timeout <= 0 || ($this->_start + $this->_timeout) > microtime(true))) {
            if (function_exists('pcntl_signal_dispatch')) {
                pcntl_signal_dispatch();
            }

            if ($this->_current = call_user_func([$this->_class, 'fetch'], $this->_options)) {
                break;
            }
            if (!$this->_terminate) {
                sleep(1);
            }
        }
    }

    /**
     * Get key for current record
     *
     * @return null
     */
    public function key()
    {
        return null;
    }

    /**
     * Check for valid record
     *
     * @return bool
     */
    public function valid()
    {
        return $this->_current !== null;
    }

    /**
     * Reset iterator
     */
    public function rewind()
    {
        $this->_start = microtime(true);
        $this->next();
    }

    /**
     * Terminate iterator
     */
    public function terminate()
    {
        $this->_terminate = true;
    }
}