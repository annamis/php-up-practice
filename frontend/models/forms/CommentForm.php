<?php

namespace frontend\models\forms;

use Yii;
use yii\base\Model;
use frontend\models\User;
use frontend\models\Comment;

class CommentForm extends Model
{

    const COMMENT_STATUS_ACTIVE = 1;

    public $content; // поле, которое создает пользователь
    private $user;
    private $post;
    private $comment;

    public function rules()
    {
        return [
            ['content', 'trim'],
            ['content', 'required'],
            [['content'], 'string', 'max' => $this->getMaxCommentLength()],
        ];
    }

    /**
     * @param User $user
     */
    public function __construct(User $user, int $postId, Comment $comment = null)
    {
        $this->user = $user;
        $this->post = $postId;
        if ($comment) {
            $this->comment = $comment;
            $this->content = $this->comment->content;
        } else {
            $this->comment = new Comment();
        }
    }

    /**
     * Maximum length of the comment
     * @return integer
     */
    private function getMaxCommentLength()
    {
        return Yii::$app->params['maxCommentContentLength'];
    }

    /**
     * @return boolean
     */
    public function save()
    {
        if ($this->validate()) {
            //берем значения из текущей формы и назначаем их свойству comment
            $this->comment->user_id = $this->user->getId();
            $this->comment->post_id = $this->post;
            $this->comment->content = $this->content;
            $this->comment->created_at = time();
            $this->comment->status = self::COMMENT_STATUS_ACTIVE;
            return $this->comment->save(false);
        }
    }

    /**
     * @return boolean
     */
    public function delete()
    {
        return $this->comment->delete();
    }

}
