<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "user_attachments".
 *
 * @property int $id
 * @property int $author_id
 * @property string $name
 * @property string $extension
 * @property string $mime
 * @property int $size
 * @property string $path
 * @property string $hash
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $author
 */
class UserAttachment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_attachments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['author_id', 'name', 'extension', 'mime', 'size', 'path', 'hash', 'created_at', 'updated_at'], 'required'],
            [['author_id', 'size'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name', 'path', 'hash'], 'string', 'max' => 100],
            [['extension', 'mime'], 'string', 'max' => 45],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['author_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'author_id' => 'Author ID',
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
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }
}
