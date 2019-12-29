<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "task_attachments".
 *
 * @property int $id
 * @property int $tasks_id
 * @property string $name
 * @property string $extension
 * @property string $mime
 * @property int $size
 * @property string $path
 * @property string $hash
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Task $tasks
 */
class TaskAttachment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task_attachments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tasks_id', 'name', 'extension', 'mime', 'size', 'path', 'hash', 'created_at', 'updated_at'], 'required'],
            [['tasks_id', 'size'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'path', 'hash'], 'string', 'max' => 100],
            [['extension', 'mime'], 'string', 'max' => 45],
            [['tasks_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['tasks_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tasks_id' => 'Tasks ID',
            'name' => 'Name',
            'extension' => 'Extension',
            'mime' => 'Mime',
            'size' => 'Size',
            'path' => 'Path',
            'hash' => 'Hash',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasOne(Task::className(), ['id' => 'tasks_id']);
    }
}
