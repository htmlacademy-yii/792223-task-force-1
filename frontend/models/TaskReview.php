<?php

namespace frontend\models;

use Carbon\Carbon;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii2mod\behaviors\CarbonBehavior;

/**
 * This is the model class for table "task_reviews".
 *
 * @property int $id
 * @property int $task_id
 * @property int $is_completed
 * @property int|null $rating
 * @property string|null $comment
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Task $task
 */
class TaskReview extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'task_reviews';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['task_id', 'is_completed'], 'required'],
            [['task_id', 'is_completed', 'rating'], 'integer'],
            [[], 'safe'],
            [['comment'], 'string', 'max' => 500],
            [['task_id', 'user_id'], 'unique', 'targetAttribute' => ['task_id', 'user_id']],
            [
                ['task_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => Task::className(),
                'targetAttribute' => ['task_id' => 'id'],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'task_id'      => 'Task ID',
            'is_completed' => 'Is Completed',
            'rating'       => 'Rating',
            'comment'      => 'Comment',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class'      => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value'      => Carbon::now('UTC')->toDateTimeString(),
            ],
            [
                'class'      => CarbonBehavior::className(),
                'attributes' => ['created_at', 'updated_at'],
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
