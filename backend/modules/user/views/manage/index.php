<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'username',
            [
                'attribute' => 'picture',
                'format' => 'raw',
                'value' => function ($user) {
                    /* @var $user \backend\models\User */
                    return Html::img($user->getImage(), ['width' => '50px']); //изображение, путь к которому мы получаем из метода getImage()
                },
            ],
            'email:email',
            'created_at:datetime',
            'updated_at',
            'nickname',
            [
                'attribute' => 'roles',
                'format' => 'raw',
                'value' => function ($user) {
                    /* @var $user \backend\models\User */
                    return implode(', ', $user->getRoles());
                },
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>
</div>
