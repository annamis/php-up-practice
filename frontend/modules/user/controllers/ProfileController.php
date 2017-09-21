<?php

namespace frontend\modules\user\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use frontend\models\User;
use Faker;

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
        return $this->render('view', [
            'user' => $this->findUser($nickname),
        ]);
    }
    
    /**
     * 
     * @param string $nickname
     * @return User
     * @throws NotFoundHttpException
     */
    public function findUser($nickname) {
        if ($user = User::find()->where(['nickname' => $nickname])->orWhere(['id' => $nickname])->one()) {
            return $user;
        }
        throw new NotFoundHttpException('User is not found');
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
