<?php

use yii\db\Migration;

/**
 * Class m180114_145818_field_type
 */
class m180114_145818_field_type extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('fields', 'type', $this->string());
        $this->addColumn('fields', 'is_default', $this->boolean());
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180114_145818_field_type cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180114_145818_field_type cannot be reverted.\n";

        return false;
    }
    */
}
