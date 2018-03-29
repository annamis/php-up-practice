<?php

namespace frontend\models\events;

use yii\base\Event;
use frontend\models\User;

class UserRecoveredEvent extends Event
{

    /**
     * @var User 
     */
    public $user;

    public function getUser(): User
    {
        return $this->user;
    }

}
