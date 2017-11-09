<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Posts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'id',
                'format' => 'raw',
                'value' => function ($post) {
                    /* @var $post \backend\models\Post */
                    return Html::a($post->id, ['view', 'id' => $post->id]); //делаем id ccылкой
                },
            ],
            'user_id',
            [
                'attribute' => 'filename',
                'format' => 'raw',
                'value' => function ($post) {
                    /* @var $post \backend\models\Post */
                    return Html::img($post->getImage(), ['width' => '130px']); //изображение, путь к которому мы получаем из метода getImage()
                },
            ],
            'description:ntext',
            'created_at:datetime',
            'complaints',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}&nbsp;&nbsp;&nbsp;{approve}&nbsp;&nbsp;&nbsp;{delete}', //формат отображения кнопок
                'buttons' => [
                    'approve' => function ($url, $post) { //кнопка отвергнуть все жалобы
                        return Html::a('<span class="glyphicon glyphicon-ok"></span>', ['approve', 'id' => $post->id], [
                                    'class' => '',
                                    'data' => [
                                        'confirm' => 'Are you absolutely sure?',
                                        'method' => 'post',
                                    ],
                            ]
                        );
                    },
                ],
            ],
        ],
    ]);
    ?>
</div>
