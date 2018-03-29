<?php
/* @var $this yii\web\View */
/* @var $post frontend\models\Post */
/* @var $model frontend\models\forms\CommentForm */
/* @var $comments frontend\models\Comment */
/* @var $currentUser frontend\models\User */

use yii\helpers\Html;
use yii\web\JqueryAsset;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\HtmlPurifier;
?>

<div class="container full">
    <div class="page-posts no-padding">
        <div class="row">
            <div class="page page-post col-sm-12 col-xs-12 post-82">
                <div class="blog-posts blog-posts-large">
                    <div class="row">

                        <!-- feed item -->
                        <article class="post col-sm-12 col-xs-12">                                            
                            <div class="post-meta">
                                <div class="post-title">
                                    <img src="<?php echo $post->getImage(); ?>" class="author-image" />
                                    <div class="author-name"><a href="<?php echo Url::to(['/user/profile/view', 'nickname' => $post->user_id]); ?>">
                                            <?php if ($post->user): ?> <!-- когда обращаемся к несуществуещему свойству, вызывается магичский метод __get, т.е. getUser() -->
                                                <?php echo $post->user->username; ?>
                                            <?php endif; ?>
                                        </a></div>
                                </div>
                            </div>
                            <div class="post-type-image">
                                <img src="<?php echo $post->getImage(); ?>" alt="">
                            </div>
                            <?php if ($post->description): ?>
                                <div class="post-description">
                                    <p><?php echo HtmlPurifier::process($post->description); ?></p>
                                </div>
                            <?php endif; ?>
                            <div class="post-bottom">
                                <a href="#" class="button-like <?php echo ($currentUser && $post->isLikedBy($currentUser)) ? "display-none" : ""; ?>" data-id="<?php echo $post->id; ?>">
                                    <i class="fa fa-lg fa-heart-o"></i>
                                </a>
                                <a href="#" class="button-unlike <?php echo ($currentUser && $post->isLikedBy($currentUser)) ? "" : "display-none"; ?>" data-id="<?php echo $post->id; ?>">
                                    <i class="fa fa-lg fa-heart"></i>
                                </a>
                                <span class="likes-count"><?php echo $post->countLikes(); ?></span>
                                <span class="post-comments"><i class="fa fa-lg fa-comment-o"></i> <?php echo $post->countComments(); ?></span>

                                <div class="post-date">
                                    <span><?php echo Yii::$app->formatter->asRelativeTime($post->created_at); ?></span>    
                                </div>
                            </div>
                        </article>
                        <!-- feed item -->


                        <div class="col-sm-12 col-xs-12">
                            <h4><?php echo $post->countComments(); ?> comments</h4>
                            <div class="comments-post">
                                <div class="single-item-title"></div>
                                <div class="row">
                                    <ul class="comment-list">
                                        
                                        <!-- comment item -->
                                        <?php foreach ($comments as $comment): ?>
                                            <li class="comment">
                                                <div class="comment-user-image">
                                                     <img src="<?php echo $comment->user->getPicture(); ?>" class="author-image" alt="Author image">
                                                </div>
                                                <div class="comment-info">
                                                    <h4 class="author">
                                                        <a href="<?php echo Url::to(['/user/profile/view', 'nickname' => ($comment->user->nickname) ? $comment->user->nickname : $comment->user->id]); ?>">
                                                            <?php echo Html::encode($comment->user->username); ?>
                                                        </a> 
                                                        <span>(<?php echo Yii::$app->formatter->asDatetime($comment->created_at); ?>)</span>
                                                    </h4>
                                                    <p><?php echo Html::encode($comment->content); ?></p>
                                                    <?php if ($currentUser && $currentUser->equals($comment->user)): ?>
                                                        <a href="<?php echo Url::to(['/post/comment/update', 'postId' => $post->id, 'commentId' => $comment->id]); ?>">
                                                            <span class="glyphicon glyphicon-pencil grey"></span>
                                                        </a>        
                                                        <a href="<?php echo Url::to(['/post/comment/delete', 'postId' => $post->id, 'commentId' => $comment->id]); ?>">
                                                            <span class="glyphicon glyphicon-remove grey"></span>
                                                        </a>      
                                                    <?php endif; ?>
                                                </div>
                                            </li>
                                        <?php endforeach; ?>
                                        <!-- comment item -->
                                        
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Comment-form -->
                        <?php if ($currentUser): ?>
                            <div class="col-sm-12 col-xs-12">
                                <div class="comment-respond">
                                    <h4>Leave a Comment</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php $form = ActiveForm::begin(); ?>

                                            <?php echo $form->field($model, 'content')->textarea(['rows' => 6])->label(false); ?>

                                            <?php echo Html::submitButton('Add comment', ['class' => 'btn btn-secondary']); ?>

                                            <?php ActiveForm::end(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <!--End comment-form -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJsFile('@web/js/likes.js', [
    'depends' => JqueryAsset::className(), //зависимость подключаемого js-файла от библиотеки Jquery
]);

