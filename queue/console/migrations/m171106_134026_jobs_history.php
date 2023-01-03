<?php

use yii\db\Migration;

/**
 * Class m171106_134026_jobs_history
 */
class m171106_134026_jobs_history extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('jobs_history', [
            'id' => $this->primaryKey(),
            'data' => $this->text(),
            'destination_id' => $this->integer(11)->null(),
            'processed_at' => $this->timestamp()->defaultValue(null),
            'processed_by' => $this->string(256)->null(),
        ], 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('jobs_history');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171106_134026_jobs_history cannot be reverted.\n";

        return false;
    }
    */
}
