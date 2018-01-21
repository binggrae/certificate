<?php

use yii\db\Migration;

/**
 * Class m180120_105845_block_template
 */
class m180120_105845_block_template extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('blocks', 'template_id', $this->integer()->notNull()->after('id'));

        $this->addForeignKey('fk_block_template',
            'blocks', 'template_id',
            'templates', 'id',
            'CASCADE', 'RESTRICT');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_block_template','blocks');
        $this->dropColumn('blocks','template_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180120_105845_block_template cannot be reverted.\n";

        return false;
    }
    */
}
