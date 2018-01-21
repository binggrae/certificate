<?php

use yii\db\Migration;

/**
 * Class m180121_085312_fix_value
 */
class m180121_085312_fix_value extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        $this->dropForeignKey('fk_certValue_cert','cert_values');
        $this->dropForeignKey('fk_certValue_block','cert_values');
        $this->dropTable('cert_values');


        $this->createTable('cert_values', [
            'cert_id' => $this->integer()->notNull(),
            'block_id' => $this->integer()->notNull(),
            'value' => $this->string(),
        ]);
        $this->addForeignKey('fk_certValue_cert',
            'cert_values', 'cert_id',
            'certificates', 'id',
            'CASCADE', 'RESTRICT');

        $this->addForeignKey('fk_certValue_block',
            'cert_values', 'block_id',
            'blocks', 'id',
            'CASCADE', 'RESTRICT');

        $this->addPrimaryKey('pk_certValues', 'cert_values', ['cert_id', 'block_id']);

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180121_085312_fix_value cannot be reverted.\n";

        return false;
    }
    */
}
