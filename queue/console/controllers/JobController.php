<?php
/**
 * Created by PhpStorm.
 * User: stas
 * Date: 13.10.2017
 * Time: 15:10
 */

namespace console\controllers;


use common\helpers\ARIterator;
use common\helpers\JobHelper;
use common\models\Job;
use yii\console\Controller;

/**
 * Jobs
 */

class JobController extends Controller
{

    /**
     * Jobs worker
     *
     * @param int  $timeout
     * @param null $only
     */
    public function actionWorker($timeout = 0, $only = null) {
        /**
         * @var Job $job
         */
        $options = [
            'only' => $only === null ? [] : explode(',', $only),
        ];
        $iterator = new ARIterator(Job::class, $timeout, $options);
        foreach ($iterator as $job) {
            echo 'Run job ' . $job->id . PHP_EOL;
            $job->run();
        }
    }

    /**
     * Do nothing, just add one test task
     */
    public function actionTest(){
        $test = new Job();
        $test->destination_id = 'Test';
        $test->data = json_encode(['test' => true]);
        $test->available_at = date('Y-m-d H:i:s');
        $test->created_at = date('Y-m-d H:i:s');
        $test->save();

        echo 'Test record created' . PHP_EOL;
    }

}