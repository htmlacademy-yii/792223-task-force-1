<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "chats".
 *
 * @property int $id
 * @property int $task_id
 * @property int $task_owner_id
 * @property int $task_agent_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ChatMessage[] $chatMessages
 * @property Task $task
 * @property User $taskOwner
 * @property User $taskAgent
 */
class Chat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chats';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_id', 'task_owner_id', 'task_agent_id', 'created_at', 'updated_at'], 'required'],
            [['task_id', 'task_owner_id', 'task_agent_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['task_id', 'task_owner_id', 'task_agent_id'], 'unique', 'targetAttribute' => ['task_id', 'task_owner_id', 'task_agent_id']],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['task_id' => 'id']],
            [['task_owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['task_owner_id' => 'id']],
            [['task_agent_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['task_agent_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'task_id' => 'Task ID',
            'task_owner_id' => 'Task Owner ID',
            'task_agent_id' => 'Task Agent ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChatMessages()
    {
        return $this->hasMany(ChatMessage::className(), ['chat_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'task_owner_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskAgent()
    {
        return $this->hasOne(User::className(), ['id' => 'task_agent_id']);
    }
}
