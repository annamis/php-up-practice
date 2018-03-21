<?php

namespace frontend\modules\user\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use frontend\models\User;
use frontend\modules\user\models\forms\PictureForm;
use yii\web\UploadedFile;
use yii\web\Response;
use yii\helpers\Url;

/**
 * Default controller for the `user` module
 */
class ProfileController extends Controller
{

    /**
     * User's profile.
     *
     * @return mixed
     */
    public function actionView($nickname)
    {
        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        $modelPicture = new PictureForm();

        return $this->render('view', [
                    'user' => $this->findUser($nickname),
                    'currentUser' => $currentUser,
                    'modelPicture' => $modelPicture,
        ]);
    }

    /**
     * 
     * @param string $nickname
     * @return User
     * @throws NotFoundHttpException
     */
    public function findUser($nickname)
    {
        if ($user = User::find()->where(['nickname' => $nickname])->orWhere(['id' => $nickname])->one()) {
            return $user;
        }
        throw new NotFoundHttpException('User is not found');
    }

    public function actionSubscribe($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        //пользователь, который хочет подписаться. вернет `identity` текущего пользователя. `Null`, если пользователь не аутентифицирован.
        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        //объект пользователя, на которого нужно подписаться
        $user = $this->findUserById($id);
        if ($currentUser->id !== $user->id) {
            $currentUser->followUser($user);
        }
        return $this->redirect(['/user/profile/view', 'nickname' => $user->getNickname()]);
    }

    /**
     * @param mixed $id
     * @return User
     * @throws NotFoundHttpException
     */
    public function findUserById($id)
    {
        if ($user = User::findOne($id)) {
            return $user;
        }
        throw NotFoundHttpException('User is not found');
    }

    public function actionUnsubscribe($id)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/user/default/login']);
        }

        //пользователь, который хочет подписаться. вернет `identity` текущего пользователя. `Null`, если пользователь не аутентифицирован.
        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        //объект пользователя, на которого нужно подписаться
        $user = $this->findUserById($id);

        if ($currentUser->id !== $user->id) {
            $currentUser->unfollowUser($user);
        }
        return $this->redirect(['/user/profile/view', 'nickname' => $user->getNickname()]);
    }

    /**
     * Handle profile image upload via ajax request
     */
    public function actionUploadPicture()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = new PictureForm();
        $model->picture = UploadedFile::getInstance($model, 'picture');

        if ($model->validate()) {

            $user = Yii::$app->user->identity;
            $user->picture = Yii::$app->storage->saveUploadedFile($model->picture); // 15/27/30379e706840f951d22de02458a4788eb55f.jpg

            if ($user->save(false, ['picture'])) {
                return [
                    'success' => true,
                    'pictureUri' => Yii::$app->storage->getFile($user->picture),
                ];
            }
        }
        return ['success' => false, 'errors' => $model->getErrors()];
    }

    /**
     * Update users profile
     * @param int $id
     * @return mixed
     */
    public function actionUpdate($id)
    {

        $model = $this->findUserById($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Profile edited');
            return $this->redirect(Url::to(['/user/profile/view', 'nickname' => $model->nickname ? $model->nickname : $model->id]));
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

}
