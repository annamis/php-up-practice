<?php

namespace frontend\components;

use yii\base\Component;
use frontend\models\Post;
use Yii;

/**
 * Post Component
 *
 * @author anna
 */
class PostService extends Component
{

    public function disablePosts(\yii\base\Event $event)
    {

        $user = $event->getUser();

        Yii::$app->db->createCommand('UPDATE post SET status=:status WHERE user_id=:user_id')
                ->bindValue(':status', Post::STATUS_DISABLED)
                ->bindValue(':user_id', $user->id)
                ->execute();
        return true;
    }

    public function recoverPosts(\yii\base\Event $event)
    {

        $user = $event->getUser();

        Yii::$app->db->createCommand('UPDATE post SET status=:status WHERE user_id=:user_id')
                ->bindValue(':status', Post::STATUS_ACTIVE)
                ->bindValue(':user_id', $user->id)
                ->execute();
        return true;
    }

}
