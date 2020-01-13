<?php

use yii\db\Migration;

/**
 * Handles the creation of table `notifications`.
 */
class m200105_181104_create_notifications_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('notifications', [
            'id'         => $this->primaryKey()->unsigned(),
            'type'       => $this->string(45)->notNull(),
            'user_id'    => $this->integer()->unsigned()->notNull(),
            'message'    => $this->string(500)->notNull(),
            'read_at'    => $this->dateTime()->null(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ]);

        $this->addForeignKey(
            'fk_notifications_users',
            'notifications',
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
            'fk_notifications_users',
            'notifications'
        );

        $this->dropTable('notifications');
    }
}
