<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\User */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
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
            'status',
            'created_at:datetime',
            'updated_at:datetime',
            'about:ntext',
            'type',
            'nickname',
            [
                'attribute' => 'roles',
                'format' => 'raw',
                'value' => function ($user) {
                    /* @var $user \backend\models\User */
                    return implode(', ', $user->getRoles());
                },
            ],
        ],
    ])
    ?>

</div>
