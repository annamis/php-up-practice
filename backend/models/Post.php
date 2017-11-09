<?php

namespace backend\models;

use Yii;
use backend\models\Feed;
use backend\models\Comment;

/**
 * This is the model class for table "post".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $filename
 * @property string $description
 * @property integer $created_at
 * @property integer $complaints
 */
class Post extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'filename' => 'Filename',
            'description' => 'Description',
            'created_at' => 'Created At',
            'complaints' => 'Complaints',
        ];
    }

    public static function findComplaints()
    {
        return Post::find()->where('complaints > 0')->orderBy('complaints DESC');
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return Yii::$app->storage->getFile($this->filename);
    }

    /**
     * Approve post (delete complaints) if it looks ok
     * @return boolean
     */
    public function approve()
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        $key = "post:{$this->id}:complaints";
        $redis->del($key);

        $this->complaints = 0;
        return $this->save(false, ['complaints']);
    }

    public function delete()
    {

        //Plan: 
        // 1. delete post
        // 2. delete related feed records
        // 3. delete from redis: 
        //   3.1 post:n:likes, 
        //   3.2 user:n:likes to post id (-get all users who liked post, -cycle all users (ids), -remove post id from user's likes list) 
        //   3.3 post:n:complaints
        // 4. delete comments

        if (parent::delete()) {
            Feed::deleteAll(['post_id' => $this->id]);

            /* @var $redis Connection */
            $redis = Yii::$app->redis;
            $ids = $redis->smembers("post:{$this->id}:likes");
            foreach ($ids as $id) {
                $redis->srem("user:{$id}:likes", $this->id);
            }
            $redis->del("post:{$this->id}:likes");
            $redis->del("post:{$this->id}:complaints");

            Comment::deleteAll(['post_id' => $this->id]);

            return true;
        }
        return false;
    }

}
