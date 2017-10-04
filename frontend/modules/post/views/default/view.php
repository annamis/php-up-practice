<?php
/* @var $this yii\web\View */
/* @var $post frontend\models\Post */

use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\ActiveForm;
?>
<div class="post-default-index">

    <div class="row">

        <div class="col-md-12">
            <!-- Получение автора публикации. Модель Post связана с моделью User через поле user_id,
            поэтому в модели Post объявляем связь в методе getUser()-->
            <?php if ($post->user): ?> <!-- когда обращаемся к несуществуещему свойству, вызывается магичский метод __get, т.е. getUser() -->
                <?php echo $post->user->username; ?>
            <?php endif; ?>
        </div>

        <div class="col-md-12">
            <img src="<?php echo $post->getImage(); ?>">
        </div>

        <div class="col-md-12">
            <?php echo Html::encode($post->description); ?>
        </div>

        <div class="col-md-12">
            <a href="#" class="button-like <?php echo ($currentUser && $post->isLikedBy($currentUser)) ? "display-none" : ""; ?>" data-id="<?php echo $post->id; ?>">
                <span class="glyphicon glyphicon-heart-empty gi-2x grey"></span>
            </a>
            <a href="#" class="button-unlike <?php echo ($currentUser && $post->isLikedBy($currentUser)) ? "" : "display-none"; ?>" data-id="<?php echo $post->id; ?>">
                <span class="glyphicon glyphicon-heart gi-2x red"></span>
            </a>
            <br>
            Likes: <span class="likes-count"><?php echo $post->countLikes(); ?></span>
        </div>
    </div>
</div>

<?php
$this->registerJsFile('@web/js/likes.js', [
    'depends' => JqueryAsset::className(), //зависимость подключаемого js-файла от библиотеки Jquery
]);

