<?php

namespace frontend\modules\post\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\forms\CommentForm;
use frontend\models\Comment;
use yii\web\NotFoundHttpException;

/**
 * Comment controller for the `post` module
 */
class CommentController extends Controller
{

    public function actionUpdate($postId, $commentId)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/user/default/login');
        }
        
        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;
        $comment = $this->findComment($commentId);

        if ($currentUser) {
            $model = new CommentForm($currentUser, $postId, $comment);
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Comment updated');
                return $this->redirect(['/post/default/view', 'id' => $postId]);
            }
        } else {
            $model = false;
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    public function actionDelete($postId, $commentId)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/user/default/login');
        }

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        $comment = $this->findComment($commentId);

        if ($currentUser) {
            $model = new CommentForm($currentUser, $postId, $comment);
            if ($model->delete()) {
                Yii::$app->session->setFlash('success', 'Comment deleted');
                return $this->redirect(['/post/default/view', 'id' => $postId]);
            }
        } else {
            $model = false;
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    public function findComment($id)
    {
        if ($comment = Comment::findOne($id)) {
            return $comment;
        }
        throw new NotFoundHttpException('Comment doesn\'t exist.');
    }

}
