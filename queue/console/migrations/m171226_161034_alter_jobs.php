<?php

use yii\db\Migration;

/**
 * Class m171226_161034_alter_jobs
 */
class m171226_161034_alter_jobs extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('jobs', 'destination_id', $this->string(128)->null());
        $this->addColumn('jobs', 'comment', $this->text());
        $this->alterColumn('jobs_history', 'destination_id', $this->string(128)->null());
        $this->addColumn('jobs_history', 'comment', $this->text());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m171226_161034_alter_jobs cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171226_161034_alter_jobs cannot be reverted.\n";

        return false;
    }
    */
}
