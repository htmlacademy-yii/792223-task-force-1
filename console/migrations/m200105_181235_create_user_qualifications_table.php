<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_qualifications`.
 */
class m200105_181235_create_user_qualifications_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user_qualifications', [
            'id'          => $this->primaryKey()->unsigned(),
            'user_id'     => $this->integer()->unsigned()->notNull(),
            'category_id' => $this->integer()->unsigned()->notNull(),
            'created_at'  => $this->dateTime()->notNull(),
            'updated_at'  => $this->dateTime()->notNull(),
        ]);

        $this->addForeignKey(
            'fk_user_qualifications_users',
            'user_qualifications',
            'user_id',
            'users',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_user_qualifications_categories',
            'user_qualifications',
            'category_id',
            'task_categories',
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
            'fk_user_qualifications_users',
            'user_qualifications'
        );

        $this->dropForeignKey(
            'fk_user_qualifications_categories',
            'user_qualifications'
        );
        $this->dropTable('user_qualifications');
    }
}
