<?php

use yii\db\Migration;

/**
 * Class m180115_080641_up
 */
class m180115_080641_up extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropTable('types');
        $this->dropTable('fields');
        $this->dropTable('blocks');

        $this->createTable('fonts', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'ttf' => $this->string()->notNull()
        ]);

        $this->createTable('types', [
            'id' => $this->primaryKey(),
            'type' => $this->string()->notNull(),
            'title' => $this->string()->notNull(),
        ]);

        $this->createTable('blocks', [
            'id' => $this->primaryKey(),
            'type_id' => $this->integer()->notNull(),
            'posX' => $this->integer()->notNull(),
            'posY' => $this->integer()->notNull(),
            'font_id' => $this->integer()->notNull(),
            'font_size' => $this->integer()->notNull(),
            'color' => $this->string()->notNull(),

        ]);

        $this->addForeignKey('fk_block_size',
            'blocks', 'type_id',
            'types', 'id',
            'CASCADE', 'RESTRICT');

        $this->addForeignKey('fk_block_font',
            'blocks', 'font_id',
            'fonts', 'id',
            'CASCADE', 'RESTRICT');

        $this->createTable('templates', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'image' => $this->string()->notNull(),
            'rate' => $this->float()->notNull(),
            'width' => $this->integer()->notNull(),
            'height' => $this->integer()->notNull(),
            'status' => $this->integer()->notNull()->defaultValue(0),
            'main' => $this->boolean()->notNull()->defaultValue(0),
        ]);

        $this->createTable('certificates', [
            'id' => $this->primaryKey(),
            'template_id' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'email' => $this->string()->notNull(),
        ]);
        $this->addForeignKey('fk_cert_template',
            'certificates', 'template_id',
            'templates', 'id',
            'CASCADE', 'RESTRICT');


        $this->createTable('cert_values', [
            'cert_id' => $this->primaryKey(),
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
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180115_080641_up cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180115_080641_up cannot be reverted.\n";

        return false;
    }
    */
}
