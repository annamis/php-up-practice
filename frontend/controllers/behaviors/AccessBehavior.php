<?php

namespace frontend\controllers\behaviors;

use Yii;
use yii\base\ActionFilter;
use frontend\models\User;

class AccessBehavior extends ActionFilter
{

    public function beforeAction($action)
    {
//        echo '<pre>';
//        print_r(Yii::$app->request->get('nickname'));
//        print_r(Yii::$app->user->identity->nickname);
//        print_r($action->controller->module->id);
//        print_r($action->controller->id);
//        print_r($action->id);
//        echo '</pre>';
//        die;
        if (Yii::$app->user->identity->status === User::STATUS_DISABLED) {
            // if: on profile page and its my page - return true;
//            if(($action->id === 'user') && ($action->controller->id === 'profile') && ($action->controller->module->id === 'view') && (Yii::$app->user->identity->nickname === Yii::$app->request->get('nickname'))) {
//            
//                echo '<pre>';
//                print_r('kek');
//                echo '</pre>';
//                die;
//            }
            Yii::$app->controller->redirect([
                '/user/profile/view',
                'nickname' => Yii::$app->user->identity->getNickname()
            ]);
            return false;
        }
    }

}
