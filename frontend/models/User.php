<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string $first_name
 * @property string $last_name
 * @property string|null $bio
 * @property string $date_of_birth
 * @property string|null $phone
 * @property string|null $skype
 * @property string|null $other_messenger
 * @property int $location_id
 * @property int $profile_views
 * @property string $last_active_at
 * @property string $created_at
 * @property string $updated_at
 *
 * @property ChatMessage[] $chatMessages
 * @property Chat[] $chats
 * @property Chat[] $chats0
 * @property Notification[] $notifications
 * @property TaskApplication[] $taskApplications
 * @property Task[] $tasks
 * @property TaskReview[] $taskReviews
 * @property Task[] $tasks0
 * @property UserAttachment[] $userAttachments
 * @property UserFavorite[] $userFavorites
 * @property UserFavorite[] $userFavorites0
 * @property User[] $favourites
 * @property User[] $users
 * @property UserQualification[] $userQualifications
 * @property TaskCategory[] $categories
 * @property UserSetting $userSetting
 * @property Location $location
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'password', 'first_name', 'last_name', 'date_of_birth', 'location_id', 'last_active_at', 'created_at', 'updated_at'], 'required'],
            [['bio'], 'string'],
            [['date_of_birth', 'last_active_at', 'created_at', 'updated_at'], 'safe'],
            [['location_id', 'profile_views'], 'integer'],
            [['email'], 'string', 'max' => 320],
            [['password', 'first_name', 'last_name'], 'string', 'max' => 100],
            [['phone', 'skype', 'other_messenger'], 'string', 'max' => 45],
            [['email'], 'unique'],
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
            'email' => 'Email',
            'password' => 'Password',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'bio' => 'Bio',
            'date_of_birth' => 'Date Of Birth',
            'phone' => 'Phone',
            'skype' => 'Skype',
            'other_messenger' => 'Other Messenger',
            'location_id' => 'Location ID',
            'profile_views' => 'Profile Views',
            'last_active_at' => 'Last Active At',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChatMessages()
    {
        return $this->hasMany(ChatMessage::className(), ['author_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChats()
    {
        return $this->hasMany(Chat::className(), ['task_owner_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChats0()
    {
        return $this->hasMany(Chat::className(), ['task_agent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotifications()
    {
        return $this->hasMany(Notification::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskApplications()
    {
        return $this->hasMany(TaskApplication::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['id' => 'task_id'])->viaTable('task_applications', ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskReviews()
    {
        return $this->hasMany(TaskReview::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTasks0()
    {
        return $this->hasMany(Task::className(), ['id' => 'task_id'])->viaTable('task_reviews', ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserAttachments()
    {
        return $this->hasMany(UserAttachment::className(), ['author_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserFavorites()
    {
        return $this->hasMany(UserFavorite::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserFavorites0()
    {
        return $this->hasMany(UserFavorite::className(), ['favourite_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFavourites()
    {
        return $this->hasMany(User::className(), ['id' => 'favourite_id'])->viaTable('user_favorites', ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('user_favorites', ['favourite_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserQualifications()
    {
        return $this->hasMany(UserQualification::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(TaskCategory::className(), ['id' => 'category_id'])->viaTable('user_qualifications', ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserSetting()
    {
        return $this->hasOne(UserSetting::className(), ['user_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation()
    {
        return $this->hasOne(Location::className(), ['id' => 'location_id']);
    }
}
