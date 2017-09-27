<?php

namespace frontend\modules\user\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use frontend\models\User;
use frontend\modules\user\models\forms\PictureForm;
use yii\web\UploadedFile;

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
        $model = new PictureForm();
        // в свойство picture загружаем экземпляр класса UploadedFile, который будет работать с изображением 
        $model->picture = UploadedFile::getInstance($model, 'picture');
        
        if ($model->validate()) {
            
            $user = Yii::$app->user->identity;
            $user->picture = Yii::$app->storage->saveUploadedFile($model->picture);
            
            if ($user->save(false, ['picture'])) { //валидация не требуется, сохранять только атрибут picture
                print_r($user->attributes); die;
            }
            
        }
        echo '<pre>';
        print_r($model->getErrors());
        echo '</pre>';
        
    }

//        public function actionGenerate()
//    {
//        $faker = \Faker\Factory::create();
//        
//        for ($i = 0; $i < 500; $i++) {
//            $user = new User([
//                'username' => $faker->name,
//                'email' => $faker->email,
//                'about' => $faker->text(200),
//                'nickname' => $faker->regexify('[A-Za-z0-9_]{5,15}'),
//                'auth_key' => Yii::$app->security->generateRandomString(),
//                'password_hash' => Yii::$app->security->generateRandomString(),
//                'created_at' => $time = time(),
//                'updated_at' => $time,
//            ]);
//            $user->save(false);
//        }
//    }
}
