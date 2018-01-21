<?php

use yii\db\Migration;

/**
 * Class m180113_113859_sert
 */
class m180113_113859_sert extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        $this->createTable('types', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'image' => $this->string()->notNull(),
            'width' => $this->integer()->notNull(),
            'height' => $this->integer()->notNull(),
            'font' => $this->string(),
            'font_css' => $this->text(),
            'status' => $this->integer(),
            'main' => $this->boolean(),
        ]);

        $this->createTable('fields', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
        ]);


        $this->createTable('blocks', [
            'id' => $this->primaryKey(),
            'type' => $this->string(),
            'posX' => $this->integer()->notNull(),
            'posY' => $this->integer()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m180113_113859_sert cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180113_113859_sert cannot be reverted.\n";

        return false;
    }
    */
}
