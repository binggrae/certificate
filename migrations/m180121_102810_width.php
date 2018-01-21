<?php

use yii\db\Migration;

/**
 * Class m180121_102810_width
 */
class m180121_102810_width extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('blocks', 'width', $this->string()->notNull());

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180121_102810_width cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180121_102810_width cannot be reverted.\n";

        return false;
    }
    */
}
