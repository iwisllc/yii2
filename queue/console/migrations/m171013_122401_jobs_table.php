<?php

use yii\db\Migration;

class m171013_122401_jobs_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('jobs', [
            'id' => $this->primaryKey(),
            'hold' => $this->integer(1)->notNull()->defaultValue(0),
            'data' => $this->text(),
            'destination_id' => $this->string(256)->notNull(),
            'available_at' => $this->timestamp()->defaultValue(null),
            'created_at' => $this->timestamp()->defaultValue(null),
            'hold_at' => $this->timestamp()->defaultValue(null),
            'processed_by' => $this->string(256)->null(),
        ], 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB');

        $this->createIndex('destination_id', 'jobs', 'destination_id');
        $this->createIndex('available_at', 'jobs', 'available_at');
        $this->createIndex('hold_at', 'jobs', 'hold_at');
    }

    public function safeDown()
    {
        $this->dropIndex('destination_id','jobs');
        $this->dropTable('jobs');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171013_122401_jobs_table cannot be reverted.\n";

        return false;
    }
    */
}
