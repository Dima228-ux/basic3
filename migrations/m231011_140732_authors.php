<?php

use yii\db\Migration;

/**
 * Class m231011_140732_authors
 */
class m231011_140732_authors extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%books_authors}}', [
            'id' => $this->primaryKey(),
            'id_authors'=>$this->integer(),
            'id_book'=>$this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%books_authors}}');
    }
}
