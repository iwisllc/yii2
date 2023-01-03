<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "jobs_history".
 *
 * @property integer $id
 * @property string $data
 * @property integer $destination_id
 * @property string $processed_at
 * @property string $processed_by
 */
class JobsHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'jobs_history';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['data', 'destination_id'], 'string'],
            [['processed_at'], 'safe'],
            [['processed_by'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'data' => Yii::t('app', 'Data'),
            'destination_id' => Yii::t('app', 'Destination ID'),
            'processed_at' => Yii::t('app', 'Processed At'),
            'processed_by' => Yii::t('app', 'Processed By'),
        ];
    }
}
