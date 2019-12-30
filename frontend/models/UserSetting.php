<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

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
            [['user_id', 'notify_task_updates', 'notify_reviews', 'notify_messages', 'show_contacts', 'show_profile'], 'required'],
            [['user_id', 'notify_task_updates', 'notify_reviews', 'notify_messages', 'show_contacts', 'show_profile'], 'integer'],
            [[], 'safe'],
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
