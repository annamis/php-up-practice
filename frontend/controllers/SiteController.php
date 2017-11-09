<?php

namespace frontend\controllers;

use yii\web\Controller;
use frontend\models\User;
use Yii;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Site controller
 */
class SiteController extends Controller
{

    public $supportedLanguages = ['en-US', 'ru-RU'];

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'language' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect('/user/default/login');
        }

        /* @var $currentUser User */
        $currentUser = Yii::$app->user->identity;

        $limit = Yii::$app->params['feedPostLimit'];
        //записи из новостной ленты для указанного пользователя
        $feedItems = $currentUser->getFeed($limit);

        return $this->render('index', [
                    'feedItems' => $feedItems,
                    'currentUser' => $currentUser,
        ]);
    }

    /**
     * About page
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Change language
     * @return mixed
     */
    public function actionLanguage()
    {
        $language = Yii::$app->request->post('language'); //получаем язык из формы
        if (in_array($language, $this->supportedLanguages)) {
            Yii::$app->language = $language; //устанавливаем тот язык, который передал пользователь
            $languageCookie = new Cookie([//устанавливаем куки
                'name' => 'language',
                'value' => $language,
                'expire' => time() + 60 * 60 * 24 * 30, // 30 days
            ]);
            Yii::$app->response->cookies->add($languageCookie); //отправляем куки пользователю
        }
        return $this->redirect(Yii::$app->request->referrer); //возвращаем пользователя на страницу, на которой он находился
    }

}
