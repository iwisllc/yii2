<?php
/**
 * Created by PhpStorm.
 * User: stas
 * Date: 13.10.2017
 * Time: 16:39
 */

namespace common\worker\Workers;


use common\models\Job;

class Test extends AbstractWorker
{

    /**
     * Run worker job
     * @param array $data
     * @param Job $job
     * @return int Result code
     */
    public function run($data, Job $job)
    {
        echo 'SOME ACTION FROM Test controller' . PHP_EOL;
        var_export($data);
        echo PHP_EOL;
        // TODO: Implement run() method.
    }
}