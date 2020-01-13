<?php

use yii\db\Migration;

/**
 * Handles the creation of table `chat_messages`.
 */
class m200105_180828_create_chat_messages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('chat_messages', [
            'id'         => $this->primaryKey()->unsigned(),
            'message'    => $this->text(),
            'chat_id'    => $this->integer()->unsigned()->notNull(),
            'author_id'  => $this->integer()->unsigned()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ]);

        $this->addForeignKey(
            'fk_chat_messages_chats',
            'chat_messages',
            'chat_id',
            'chats',
            'id',
            'NO ACTION',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_chat_messages_users',
            'chat_messages',
            'author_id',
            'users',
            'id',
            'NO ACTION',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk_chat_messages_chats',
            'chat_messages'
        );

        $this->dropForeignKey(
            'fk_chat_messages_users',
            'chat_messages'
        );

        $this->dropTable('chat_messages');
    }
}
