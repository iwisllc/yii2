<?php

use yii\db\Migration;

/**
 * Class m180208_104359_alter_job_fields
 */
class m180208_104359_alter_job_fields extends Migration
{

    use \common\models\TextTypesTrait;

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('jobs', 'data', $this->mediumText());
        $this->alterColumn('jobs_history', 'data', $this->mediumText());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180208_104359_alter_job_fields cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180208_104359_alter_job_fields cannot be reverted.\n";

        return false;
    }
    */
}
