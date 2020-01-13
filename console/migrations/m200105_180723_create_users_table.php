<?php

use yii\db\Migration;

/**
 * Handles the creation of table `users`.
 */
class m200105_180723_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('users', [
            'id'              => $this->primaryKey()->unsigned(),
            'email'           => $this->string(320)->notNull()->unique(),
            'password'        => $this->string(100)->notNull(),
            'first_name'      => $this->string(100)->notNull(),
            'last_name'       => $this->string(100)->notNull(),
            'bio'             => $this->text()->null(),
            'date_of_birth'   => $this->dateTime()->notNull(),
            'phone'           => $this->string(45)->null(),
            'skype'           => $this->string(45)->null(),
            'other_messenger' => $this->string(45)->null(),
            'location_id'     => $this->integer()->unsigned()->notNull(),
            'profile_views'   => $this->integer()->unsigned()->notNull()->defaultValue(0),
            'last_active_at'  => $this->dateTime()->notNull(),
            'created_at'      => $this->dateTime()->notNull(),
            'updated_at'      => $this->dateTime()->notNull(),
        ]);

        $this->addForeignKey(
            'fk_users_locations',
            'users',
            'location_id',
            'locations',
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
            'fk_users_locations',
            'users'
        );

        $this->dropTable('users');
    }
}
