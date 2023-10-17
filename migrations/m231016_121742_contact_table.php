<?php

use yii\db\Migration;

/**
 * Class m231016_121742_contact_table
 */
class m231016_121742_contact_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%contact}}', [
            'id' => $this->primaryKey(),
            'name'=>$this->string(60),
            'email'=>$this->string(60),
            'subject'=>$this->text(),
            'body'=>$this->text(),
            'phone'=>$this->string(30),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%contact}}');
    }
}
