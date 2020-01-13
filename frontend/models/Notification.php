<?php

namespace frontend\models;

use Carbon\Carbon;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii2mod\behaviors\CarbonBehavior;

/**
 * This is the model class for table "notifications".
 *
 * @property int $id
 * @property string $type
 * @property int $user_id
 * @property string $message
 * @property string|null $read_at
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 */
class Notification extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notifications';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'user_id', 'message'], 'required'],
            [['user_id'], 'integer'],
            [['read_at'], 'safe'],
            [['type'], 'string', 'max' => 45],
            [['message'], 'string', 'max' => 500],
            [
                ['user_id'],
                'exist',
                'skipOnError'     => true,
                'targetClass'     => User::className(),
                'targetAttribute' => ['user_id' => 'id'],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'      => 'ID',
            'type'    => 'Type',
            'user_id' => 'User ID',
            'message' => 'Message',
            'read_at' => 'Read At',
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
                'attributes' => ['created_at', 'updated_at', 'read_at'],
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
