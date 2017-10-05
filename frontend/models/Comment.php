<?php

namespace frontend\models;

/**
 * This is the model class for table "comment".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $post_id
 * @property string $content
 * @property integer $created_at
 * @property integer $status
 */
class Comment extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'post_id' => 'Post ID',
            'content' => 'Content',
            'created_at' => 'Created At',
            'status' => 'Status',
        ];
    }

    /**
     * Get author of the comment
     * @return User|null
     */
    public function getUser()
    {
        // у каждого коммента может быть 1 автор
        return $this->hasOne(User::className(), ['id' => 'user_id'])->one();
    }

}
