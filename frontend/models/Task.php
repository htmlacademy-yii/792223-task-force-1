<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property int $owner_id
 * @property string $status
 * @property int|null $agent_id
 * @property string $name
 * @property string $description
 * @property int $price
 * @property string $expired_at
 * @property int $category_id
 * @property int|null $location_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Chat[] $chats
 * @property TaskApplication[] $taskApplications
 * @property User[] $users
 * @property TaskAttachment[] $taskAttachments
 * @property TaskReview[] $taskReviews
 * @property User[] $users0
 * @property TaskCategory $category
 * @property Location $location
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['owner_id', 'status', 'name', 'description', 'price', 'expired_at', 'category_id', 'created_at', 'updated_at'], 'required'],
            [['owner_id', 'agent_id', 'price', 'category_id', 'location_id'], 'integer'],
            [['status', 'description'], 'string'],
            [['expired_at', 'created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => TaskCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['location_id'], 'exist', 'skipOnError' => true, 'targetClass' => Location::className(), 'targetAttribute' => ['location_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'owner_id' => 'Owner ID',
            'status' => 'Status',
            'agent_id' => 'Agent ID',
            'name' => 'Name',
            'description' => 'Description',
            'price' => 'Price',
            'expired_at' => 'Expired At',
            'category_id' => 'Category ID',
            'location_id' => 'Location ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChats()
    {
        return $this->hasMany(Chat::className(), ['task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskApplications()
    {
        return $this->hasMany(TaskApplication::className(), ['task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('task_applications', ['task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskAttachments()
    {
        return $this->hasMany(TaskAttachment::className(), ['tasks_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskReviews()
    {
        return $this->hasMany(TaskReview::className(), ['task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers0()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('task_reviews', ['task_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(TaskCategory::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(Location::className(), ['id' => 'location_id']);
    }
}
