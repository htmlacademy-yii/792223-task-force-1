<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_attachments`.
 */
class m200105_181209_create_user_attachments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user_attachments', [
            'id'         => $this->primaryKey()->unsigned(),
            'user_id'    => $this->integer()->unsigned()->notNull(),
            'name'       => $this->string(100)->notNull(),
            'extension'  => $this->string(45)->notNull(),
            'mime'       => $this->string(45)->notNull(),
            'size'       => $this->integer()->unsigned()->notNull(),
            'path'       => $this->string(100)->notNull(),
            'hash'       => $this->string(100)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ]);

        $this->addForeignKey(
            'fk_user_attachments_users',
            'user_attachments',
            'user_id',
            'users',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk_user_attachments_users',
            'user_attachments'
        );

        $this->dropTable('user_attachments');
    }
}
