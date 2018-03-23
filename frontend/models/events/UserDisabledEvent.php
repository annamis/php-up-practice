<?php

namespace frontend\models\events;

use yii\base\Event;
use frontend\models\User;
use frontend\models\Post;

class UserDisabledEvent extends Event
{

    /**
     * @var User 
     */
    public $user;

    /**
     *
     * @var Post
     */
    public $post;

    public function getUser(): User
    {
        return $this->user;
    }

}
