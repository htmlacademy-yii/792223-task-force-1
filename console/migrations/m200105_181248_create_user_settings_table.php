<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_settings`.
 */
class m200105_181248_create_user_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user_settings', [
            'user_id'             => $this->primaryKey()->unsigned(),
            'notify_task_updates' => $this->tinyInteger()->notNull()->defaultValue(1),
            'notify_reviews'      => $this->tinyInteger()->notNull()->defaultValue(1),
            'notify_messages'     => $this->tinyInteger()->notNull()->defaultValue(1),
            'show_contacts'       => $this->tinyInteger()->notNull()->defaultValue(1),
            'show_profile'        => $this->tinyInteger()->notNull()->defaultValue(1),
            'created_at'          => $this->dateTime()->notNull(),
            'updated_at'          => $this->dateTime()->notNull(),
        ]);

        $this->addForeignKey(
            'fk_user_settings_users',
            'user_settings',
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
            'fk_user_settings_users',
            'user_settings'
        );

        $this->dropTable('user_settings');
    }
}
