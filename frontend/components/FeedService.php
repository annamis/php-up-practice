<?php

namespace frontend\components;

use yii\base\Component;
use frontend\models\Feed;
use Yii;

/**
 * Feed Component
 * Класс, который занимается лентой новостей
 *
 * @author anna
 */
class FeedService extends Component
{

    public function addToFeeds(\yii\base\Event $event)
    {
        $user = $event->getUser();
        $post = $event->getPost();

        $followers = $user->getFollowers();

        foreach ($followers as $follower) {
            //для каждого подписчика добавляем новую запись в таблицу Feed
            $feedItem = new Feed();
            //id подписчика, в ленту которого нужно добавить запись
            $feedItem->user_id = $follower['id'];
            $feedItem->author_id = $user->id;
            $feedItem->author_name = $user->username;
            $feedItem->author_nickname = $user->getNickname();
            $feedItem->author_picture = $user->getPicture();
            $feedItem->post_id = $post->id;
            $feedItem->post_filename = $post->filename;
            $feedItem->post_description = $post->description;
            $feedItem->post_created_at = $post->created_at;
            $feedItem->status = $post->created_at;
            $feedItem->save();
        }
    }

    public function disableFeeds(\yii\base\Event $event)
    {

        $user = $event->getUser();

        Yii::$app->db->createCommand('UPDATE feed SET status=:status WHERE author_id=:author_id')
                ->bindValue(':status', Feed::STATUS_DISABLED)
                ->bindValue(':author_id', $user->id)
                ->execute();
        return true;
    }
    
    public function recoverFeeds(\yii\base\Event $event)
    {

        $user = $event->getUser();

        Yii::$app->db->createCommand('UPDATE feed SET status=:status WHERE author_id=:author_id')
                ->bindValue(':status', Feed::STATUS_ACTIVE)
                ->bindValue(':author_id', $user->id)
                ->execute();
        return true;
    }

}
