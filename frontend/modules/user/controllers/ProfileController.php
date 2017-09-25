<?php

namespace frontend\modules\user\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use frontend\models\User;

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

        return $this->render('view', [
                    'user' => $this->findUser($nickname),
                    'currentUser' => $currentUser,
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
