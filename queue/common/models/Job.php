<?php

namespace common\models;

use common\helpers\EmergencyException;
use common\worker\Exception\Fail;
use common\worker\Exception\Ignore;
use common\worker\Exception\Wait;
use common\worker\Workers\AbstractWorker;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\db\Transaction;

/**
 * Class Job
 *
 * @property int         $id
 * @property bool        $hold
 * @property string      $data
 * @property int         $event_id
 * @property int         $operation_id
 * @property int         $destination_id
 * @property string      $available_at
 * @property string      $created_at
 * @property string      $hold_at
 * @property string|null $processed_by
 */
class Job extends ActiveRecord
{
    /**
     * Declares the name of the database table associated with this AR class.
     *
     * @return string
     */
    public static function tableName()
    {
        return 'jobs';
    }

    /**
     * Returns the primary key name(s) for this AR class.
     *
     * @return string
     */
    public static function primaryKey()
    {
        return [ 'id' ];
    }

    /**
     * Run this job
     */
    public function run()
    {
        try {
            AbstractWorker::dispatch($this->destination_id, '', json_decode($this->data, true), $this);

            $history = new JobsHistory();
            $history->data = $this->data;
            $history->destination_id = $this->destination_id;
            $history->processed_at = new Expression('NOW()');
            $history->processed_by = Yii::$app->params['serverName'];
            if ( !$history->save() ) {
                echo 'History not saved!';
                var_dump($history->errors);
            }

            $this->delete();
        } catch ( Fail $e ) {
            echo 'Fail: ' . $e->getMessage() . PHP_EOL;
            $this->updateAttributes([
                'comment' => $e->getMessage(),
            ]);
            new EmergencyException('Job failed', 0, $e, $this);
        } catch ( Wait $e ) {
            echo 'Wait: ' . $e->getMessage() . PHP_EOL;
            $data = [
                'hold'         => 0,
                'hold_at'      => null,
                'comment'      => $e->getMessage(),
                'processed_by' => Yii::$app->params['serverName'],
            ];
            if ( !$e->fast ) {
                $data['available_at'] = new Expression('DATE_ADD(NOW(),INTERVAL 10 MINUTE)');
            }
            $this->updateAttributes($data);
            new EmergencyException('Job delayed', 0, $e, $this);
        } catch ( Ignore $e ) {
            echo 'Ignore: ' . $e->getMessage() . PHP_EOL;
            $this->delete();
            new EmergencyException('Job Ignored', 0, $e, $this);
        } catch ( \Exception $e ) {
            echo 'Job exeption ' . $e->getMessage() . PHP_EOL;
            new EmergencyException('Exception on job', 0, $e, $this);
        } catch ( \Throwable $e ) {
            echo 'Job exeption ' . $e->getMessage() . PHP_EOL;
            new EmergencyException('Exception on job, ' . $e, 0, $e, $this);
        }
    }

    /**
     * Fetch next job
     *
     * @param array $options
     *
     * @return null|\yii\db\ActiveRecord
     * @throws \Throwable
     */
    public static function fetch(array $options = [])
    {
        return self::getDb()->transaction(function () use ($options) {
            $query = Job::find()
                ->where([ 'hold' => 0 ])
                //->where(['hold' => 0, 'processed_by' => array_key_exists('hostname', \Yii::$app->params) ? \Yii::$app->params['hostname'] : ''])
                ->andWhere('available_at <= now()')
                ->orderBy('available_at');
            if ( array_key_exists('only', $options) && is_array($options['only']) && count($options['only']) !== 0 ) {
                $query->andWhere([ 'in', 'destination_id', $options['only'] ]);
            }

            $source = $query->one();
            if ( $source ) {
                $source->updateAttributes([
                    'hold'    => 1,
                    'hold_at' => new Expression('now()'),
                ]);
            }

            return $source;
        }, Transaction::SERIALIZABLE);
    }

    protected function getReportFields()
    {
        return [
            'Id'          => 'id',
            'Created'     => 'created_at',
            'Available'   => 'available_at',
            'Hold'        => function () {
                return $this->hold ? $this->hold_at : 'no';
            },
            'Data'        => function () {
                return json_decode($this->data, true);
            },
            'Destination' => 'destination_',
            'Event'       => 'event_',
        ];
    }
}