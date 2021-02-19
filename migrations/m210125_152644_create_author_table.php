<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%author}}`.
 */
class m210125_152644_create_author_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%author}}', [
            'id' => $this->primaryKey(),
            'author' => $this->string(),
            'kolvo' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%author}}');
    }
}
