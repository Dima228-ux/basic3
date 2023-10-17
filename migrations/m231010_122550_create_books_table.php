<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%books}}`.
 */
class m231010_122550_create_books_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%books}}', [
            'id' => $this->primaryKey(),
            'title'=> $this->text(),
            'isbn'=> $this->string(15),
            'pageCount'=>$this->integer(),
            'publishedDate'=>$this->string(40),
            'thumbnail'=>$this->string(50),
            'shortDescription'=>$this->text(),
            'longDescription'=>$this->text(),
            'status'=>$this->string(15),
            'new_record'=>$this->boolean(),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%books}}');
    }
}
