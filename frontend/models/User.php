<?php

namespace frontend\models;

use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\behaviors\TimestampBehavior;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $about
 * @property integer $type
 * @property string $nickname
 * @property string $picture
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const DEFAULT_IMAGE = '/img/profile_default_image.jpg';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'email', 'about'], 'safe'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
            ['nickname', 'string', 'length' => [5, 15]],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => Yii::t('user', 'ID'),
            'username' => Yii::t('user', 'Username'),
            'email' => Yii::t('user', 'Email'),
            'about' => Yii::t('user', 'About'),
            'nickname' => Yii::t('user', 'Nickname'),
            'picture' => Yii::t('user', 'Picture'),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
                    'password_reset_token' => $token,
                    'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @return mixed
     */
    public function getNickname()
    {
        return ($this->nickname) ? $this->nickname : $this->getId();
    }

    /**
     * Subscribe current user to given user
     * @param \frontend\models\User $user
     */
    public function followUser(User $user)
    {

        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        //в список подписок добавить подписчика и наоборот
        $redis->sadd("user:{$this->getId()}:subscriptions", $user->getId());
        $redis->sadd("user:{$user->getId()}:followers", $this->getId());
    }

    /**
     * Unsubscribe current user from given user
     * @param \frontend\models\User $user
     */
    public function unfollowUser(User $user)
    {

        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        //в список подписок добавить подписчика и наоборот
        $redis->srem("user:{$this->getId()}:subscriptions", $user->getId());
        $redis->srem("user:{$user->getId()}:followers", $this->getId());
    }

    /**
     * @return array
     */
    public function getSubscriptions()
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        $key = "user:{$this->getId()}:subscriptions";
        $ids = $redis->smembers($key);
        return User::find()->select('id, username, nickname')->where(['id' => $ids])->orderBy('username')->asArray()->all();
    }

    /**
     * @return array
     */
    public function getFollowers()
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        $key = "user:{$this->getId()}:followers";
        $ids = $redis->smembers($key);
        return User::find()->select('id, username, nickname')->where(['id' => $ids])->orderBy('username')->asArray()->all();
    }

    public function countSubscriptions()
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        $key = "user:{$this->getId()}:subscriptions";
        return $redis->scard($key);
    }

    public function countFollowers()
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        $key = "user:{$this->getId()}:followers";
        return $redis->scard($key);
    }

    /**
     * @param \frontend\models\User $user
     * @return array
     */
    public function getMutualSubscriptionsTo(User $user)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;

        //current user subscriptions
        $key1 = "user:{$this->getId()}:subscriptions";
        //given user followers
        $key2 = "user:{$user->getId()}:followers";
        $ids = $redis->sinter($key1, $key2);
        $users = User::find()->select('id, username, nickname')->where(['id' => $ids])->orderBy('username')->asArray()->all();
        unset($users[$this->getId()]);
        return $users;
    }

    /**
     * @param \frontend\models\User $user
     * @return bool
     */
    public function checkSubscription(User $user)
    {

        /* @var $redis Connection */
        $redis = Yii::$app->redis;

        //проверить входит ли id пользователя1 в множество подписчиков пользователя2
        if ($redis->sismember("user:{$this->getId()}:subscriptions", $user->getId())) {
            return true;
        }
        return false;
    }

    /**
     * Get profile picture
     * @return string
     */
    public function getPicture()
    {
        if ($this->picture) {
            return Yii::$app->storage->getFile($this->picture);
        }
        return self::DEFAULT_IMAGE;
    }

    /**
     * Get data for newsfeed
     * @param integer $limit
     * @return array
     */
    public function getFeed(int $limit)
    {
        $order = ['post_created_at' => SORT_DESC]; //по дате по спаданию
        //для каждого пользователя можно найти несколько записей в таблице Feed 1:M
        return $this->hasMany(Feed::className(), ['user_id' => 'id'])->orderBy($order)->limit($limit)->all();
    }

    /**
     * Check whether current user likes post with given id
     * @param integer $postId
     * @return boolean
     */
    public function likesPost(int $postId)
    {
        /* @var $redis Connection */
        $redis = Yii::$app->redis;
        return (bool) $redis->sismember("user:{$this->getId()}:likes", $postId);
    }

    /**
     * @return array|Post[]
     */
    public function getPosts()
    {
        return $this->hasMany(Post::className(), ['user_id' => 'id'])->all();
    }

    /**
     * @return integer
     */
    public function countPosts()
    {
        return $this->hasMany(Post::className(), ['user_id' => 'id'])->count();
    }

}
