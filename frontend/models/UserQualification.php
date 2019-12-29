<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "user_qualifications".
 *
 * @property int $id
 * @property int $user_id
 * @property int $category_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property TaskCategory $category
 * @property User $user
 */
class UserQualification extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_qualifications';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'category_id', 'created_at', 'updated_at'], 'required'],
            [['user_id', 'category_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['user_id', 'category_id'], 'unique', 'targetAttribute' => ['user_id', 'category_id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => TaskCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
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
            'category_id' => 'Category ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
