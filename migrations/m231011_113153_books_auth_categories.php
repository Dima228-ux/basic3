<?php

use yii\db\Migration;

/**
 * Class m231011_113153_books_auth_categories
 */
class m231011_113153_books_auth_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%books_categories}}', [
            'id' => $this->primaryKey(),
            'id_category'=>$this->integer(),
            'id_book'=>$this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%books_categories}}');
    }
}
