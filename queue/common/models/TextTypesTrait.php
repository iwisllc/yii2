<?php
/**
 * Created by PhpStorm.
 * User: stas
 * Date: 08.02.2018
 * Time: 12:42
 */

namespace common\models;


trait TextTypesTrait
{
    /**
     * @return \yii\db\Connection the database connection to be used for schema building.
     */
    protected abstract function getDb();

    /**
     * Creates a medium text column.
     * @return ColumnSchemaBuilder the column instance which can be further customized.
     */
    public function mediumText()
    {
        return $this->getDb()->getSchema()->createColumnSchemaBuilder('mediumtext');
    }

    /**
     * Creates a long text column.
     * @return ColumnSchemaBuilder the column instance which can be further customized.
     */
    public function longText()
    {
        return $this->getDb()->getSchema()->createColumnSchemaBuilder('longtext');
    }

    /**
     * Creates a tiny text column.
     * @return ColumnSchemaBuilder the column instance which can be further customized.
     */
    public function tinyText()
    {
        return $this->getDb()->getSchema()->createColumnSchemaBuilder('tinytext');
    }
}