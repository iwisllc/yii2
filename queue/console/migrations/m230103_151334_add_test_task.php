<?php

use yii\db\Migration;

/**
 * Class m230103_151334_add_test_task
 */
class m230103_151334_add_test_task extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('jobs', [
            'data' => json_encode(['test' => true]),
            'destination_id' => 'Test',
            'available_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m230103_151334_add_test_task cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230103_151334_add_test_task cannot be reverted.\n";

        return false;
    }
    */
}
