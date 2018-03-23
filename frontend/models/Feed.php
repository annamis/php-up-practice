<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "feed".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $author_id
 * @property string $author_name
 * @property integer $author_nickname
 * @property string $author_picture
 * @property integer $post_id
 * @property string $post_filename
 * @property string $post_description
 * @property integer $post_created_at
 */
class Feed extends \yii\db\ActiveRecord
{

    const STATUS_DELETED = 0;
    const STATUS_DISABLED = 5;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'feed';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'author_id' => 'Author ID',
            'author_name' => 'Author Name',
            'author_nickname' => 'Author Nickname',
            'author_picture' => 'Author Picture',
            'post_id' => 'Post ID',
            'post_filename' => 'Post Filename',
            'post_description' => 'Post Description',
            'post_created_at' => 'Post Created At',
        ];
    }

    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED, self::STATUS_DISABLED]],
        ];
    }

    /**
     * @return mixed
     */
    public function countLikes()
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        return $redis->scard("post:{$this->post_id}:likes");
    }

    /**
     * @return int
     */
    public function countComments()
    {
        return $this->hasMany(Comment::className(), ['post_id' => 'post_id'])->count();
    }

    /**
     * @param \frontend\models\User $user
     * @return boolean
     */
    public function isReported(User $user)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        return $redis->sismember("post:{$this->post_id}:complaints", $user->getId());
    }

}
