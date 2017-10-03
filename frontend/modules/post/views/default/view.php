<?php
/* @var $this yii\web\View */
/* @var $post frontend\models\Post */

use yii\helpers\Html;
?>
<div class="post-default-index">

    <div class="row">

        <div class="col-md-12">
            <!-- Получение автора публикации. Модель Post связана с моделью User через поле user_id,
            поэтому в модели Post объявляем связь в методе getUser()-->
            <?php if ($post->user): ?> <!-- когда обращаемся к несуществуещему свойству, вызывается магичский метод __get, т.е. getUser() -->
            `<?php echo $post->user->username; ?>
            <?php endif; ?>
        </div>

        <div class="col-md-12">
            <img src="<?php echo $post->getImage(); ?>">
        </div>

        <div class="col-md-12">
            <?php echo Html::encode($post->description); ?>
        </div>

    </div>
</div>

