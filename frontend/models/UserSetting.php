<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "user_settings".
 *
 * @property int $user_id
 * @property int $notify_task_updates
 * @property int $notify_reviews
 * @property int $notify_messages
 * @property int $show_contacts
 * @property int $show_profile
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 */
class UserSetting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'notify_task_updates', 'notify_reviews', 'notify_messages', 'show_contacts', 'show_profile', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'notify_task_updates', 'notify_reviews', 'notify_messages', 'show_contacts', 'show_profile'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['user_id'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'notify_task_updates' => 'Notify Task Updates',
            'notify_reviews' => 'Notify Reviews',
            'notify_messages' => 'Notify Messages',
            'show_contacts' => 'Show Contacts',
            'show_profile' => 'Show Profile',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
