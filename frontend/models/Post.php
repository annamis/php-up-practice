<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "post".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $filename
 * @property string $description
 * @property integer $created_at
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
        ];
    }

    public function getImage()
    {
        return Yii::$app->storage->getFile($this->filename);
    }

    /**
     * Get author of the post
     * @return User|null
     */
    public function getUser()
    {
        // у каждого поста может быть 1 автор
        return $this->hasOne(User::className(), ['id' => 'user_id'])->one();
    }

    /**
     * Like current post by given user
     * @param \frontend\models\User $user
     */
    public function like(User $user)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        //"post: {$this->getId()}:likes" - список пользователей, которые лайкнули пост
        $redis->sadd("post:{$this->getId()}:likes", $user->getId());
        //"user: {$user->getId()}:likes" - список постов, которые лайкнул пользователь
        $redis->sadd("user:{$user->getId()}:likes", $this->getId());
    }

    /**
     * Unlike current post by given user
     * @param \frontend\models\User $user
     */
    public function unLike(User $user)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        $redis->srem("post:{$this->getId()}:likes", $user->getId());
        $redis->srem("user:{$user->getId()}:likes", $this->getId());
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function countLikes()
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        //подсчитывает количество элементов в множеcтве
        return $redis->scard("post:{$this->getId()}:likes");
    }

    /**
     * @return int
     */
    public function countComments()
    {
        return $this->hasMany(Comment::className(), ['post_id' => 'id'])->count();
    }

    /**
     * Check whether given user liked current post
     * @param \frontend\models\User $user
     * @return integer
     */
    public function isLikedBy(User $user)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        //является ли идентификатор пользователя одним из членов множества лайков поста
        return $redis->sismember("post:{$this->getId()}:likes", $user->getId());
    }

    /**
     * Add complaint to post from given user
     * @param \frontend\models\User $user
     * @return boolean
     */
    public function complain(User $user)
    {
         /* @var $redis Connection */
        $redis = Yii::$app->redis;
        $key = "post:{$this->getId()}:complaints"; //формируем ключ post:10:complaints
        
        if (!$redis->sismember($key, $user->getId())) { //если в множестве нет id пользователя (еще не жаловался)
            $redis->sadd($key, $user->getId());        
            $this->complaints++; //увеличиваем счетчик жалоб
            return $this->save(false, ['complaints']); //сохраняем этот счетчик в таблице post в поле complaints 
        }
    }

// fk-auth-user_id-user-id
}
