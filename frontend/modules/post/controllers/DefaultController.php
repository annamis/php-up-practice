<?php

namespace frontend\modules\post\controllers;

use Yii;
use yii\web\Controller;
use frontend\modules\post\models\forms\PostForm;
use yii\web\UploadedFile;
use frontend\models\Post;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `post` module
 */
class DefaultController extends Controller
{

    /**
     * Renders the create view for the module
     * @return type
     */
    public function actionCreate()
    {
        $model = new PostForm(Yii::$app->user->identity);

        if ($model->load(Yii::$app->request->post())) {

            //указываем, что атрибут модели picture это экземпляр класса UploadedFile,
            // в котором находятся данные из формы в поле picture
            $model->picture = UploadedFile::getInstance($model, 'picture');

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Post created');
                return $this->goHome();
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    public function actionView($id)
    {

        return $this->render('view', [
                    'post' => $this->findPost($id),
        ]);
    }

    //задача: проверить на null (и выкинуть ошибку) или вернуть объект. ПОИСКОМ ЗАНИМАЕТСЯ POST::
    public function findPost($id)
    {
        if ($user = Post::findOne($id)) {
            return $user;  
        }
        throw new NotFoundHttpException('Post doesn\'t exist.');
    }

}
