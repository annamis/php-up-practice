<?php
/* @var $this yii\web\View */
/* @var $post frontend\models\Post */
/* @var $model frontend\models\forms\CommentForm */
/* @var $comments frontend\models\Comment */

use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<div class="post-default-view">

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
            <a href="#" class="button-comment" data-id="<?php echo $post->id; ?>">
                <span class="glyphicon glyphicon-comment gi-2x grey"></span>
            </a>
            <br>
            Likes: <span class="likes-count"><?php echo $post->countLikes(); ?></span>
            Comments: <span class="comments-count"><?php echo $post->countComments(); ?></span>
        </div>
    </div>

    <?php if ($currentUser): ?>
        <div class="row">
            <div class="col-md-6">
                <?php $form = ActiveForm::begin(); ?>

                <?php echo $form->field($model, 'content')->textarea(['rows' => 2])->label(''); ?>

                <?php echo Html::submitButton('Add comment', ['class' => 'btn btn-default']); ?>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    <?php endif; ?>

    <div id="commentBlock">
        <?php foreach ($comments as $comment): ?>
            <div>
                <p><?php echo Html::encode($comment->user->username); ?> </p>
                <h6><?php echo Yii::$app->formatter->asDatetime($comment->created_at, 'long'); ?></h6>
                <p><?php echo Html::encode($comment->content); ?> 
                    
                    <?php if($currentUser && $currentUser->equals($post->user)): ?>
                    <a href="<?php echo Url::to(['/post/comment/update', 'postId' => $post->id, 'commentId' => $comment->id]); ?>">
                        <span class="glyphicon glyphicon-pencil grey"></span>
                    </a>        
                    <a href="<?php echo Url::to(['/post/comment/delete', 'postId' => $post->id, 'commentId' => $comment->id]); ?>">
                        <span class="glyphicon glyphicon-remove grey"></span>
                    </a>      
                    <?php endif; ?>
                    
                </p>
                <hr> 
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php
$this->registerJsFile('@web/js/likes.js', [
    'depends' => JqueryAsset::className(), //зависимость подключаемого js-файла от библиотеки Jquery
]);
$this->registerJsFile('@web/js/comment.js', [
    'depends' => JqueryAsset::className(), //зависимость подключаемого js-файла от библиотеки Jquery
]);

