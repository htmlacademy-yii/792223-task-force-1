<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "user_favorites".
 *
 * @property int $id
 * @property int $user_id
 * @property int $favourite_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 * @property User $favourite
 */
class UserFavorite extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_favorites';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'favourite_id', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'favourite_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['user_id', 'favourite_id'], 'unique', 'targetAttribute' => ['user_id', 'favourite_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['favourite_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['favourite_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'favourite_id' => 'Favourite ID',
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFavourite()
    {
        return $this->hasOne(User::className(), ['id' => 'favourite_id']);
    }
}
