<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "task_attachments".
 *
 * @property int $id
 * @property int $task_id
 * @property string $name
 * @property string $extension
 * @property string $mime
 * @property int $size
 * @property string $path
 * @property string $hash
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Task $task
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
            [['task_id', 'name', 'extension', 'mime', 'size', 'path', 'hash'], 'required'],
            [['task_id', 'size'], 'integer'],
            [[], 'safe'],
            [['name', 'path', 'hash'], 'string', 'max' => 100],
            [['extension', 'mime'], 'string', 'max' => 45],
            [['task_id'], 'exist', 'skipOnError' => true, 'targetClass' => Task::className(), 'targetAttribute' => ['task_id' => 'id']],
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
            'name' => 'Name',
            'extension' => 'Extension',
            'mime' => 'Mime',
            'size' => 'Size',
            'path' => 'Path',
            'hash' => 'Hash',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at']
                ],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }
}
