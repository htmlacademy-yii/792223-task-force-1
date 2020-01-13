<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_favorites`.
 */
class m200105_181222_create_user_favorites_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user_favorites', [
            'id'          => $this->primaryKey()->unsigned(),
            'user_id'     => $this->integer()->unsigned()->notNull(),
            'favorite_id' => $this->integer()->unsigned()->notNull(),
            'created_at'  => $this->dateTime()->notNull(),
            'updated_at'  => $this->dateTime()->notNull(),
        ]);

        $this->addForeignKey(
            'fk_user_favorites_users1',
            'user_favorites',
            'user_id',
            'users',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_user_favorites_users2',
            'user_favorites',
            'favorite_id',
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
            'fk_user_favorites_users1',
            'user_favorites'
        );

        $this->dropForeignKey(
            'fk_user_favorites_users2',
            'user_favorites'
        );

        $this->dropTable('user_favorites');
    }
}
