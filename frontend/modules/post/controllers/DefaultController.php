<?php

namespace frontend\modules\post\controllers;

use Yii;
use yii\web\Controller;
use frontend\modules\post\models\forms\PostForm;
use yii\web\UploadedFile;
use frontend\models\Post;
use yii\web\NotFoundHttpException;
use yii\web\Response;

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
        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;
        
        return $this->render('view', [
                    'post' => $this->findPost($id),
                    'currentUser' => $currentUser,
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

    public function actionLike()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/user/default/login');
        }

        //формат ответа json
        Yii::$app->response->format = Response::FORMAT_JSON;

        //чтобы увеличивать кол-во лайков нужно знать: какой пост (передать id в кнопке) и кто лайкнул (текущий пользователь).
        // читаем id, отправленный в заголовке
        $id = Yii::$app->request->post('id'); //Yii::$app->request->post() gives you all incoming POST-data
        //находим пост по id
        $post = $this->findPost($id);

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        //метод like увеличивает количество лайков у указанного поста
        $post->like($currentUser);

        //ответ
        return [
            'success' => true,
            'likesCount' => $post->countLikes(),
        ];
    }

    public function actionUnlike()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        $id = Yii::$app->request->post('id');

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;
        $post = $this->findPost($id);

        $post->unLike($currentUser);

        return [
            'success' => true,
            'likesCount' => $post->countLikes(),
        ];
    }

}
