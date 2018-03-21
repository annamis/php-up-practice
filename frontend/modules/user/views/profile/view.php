<?php
/* @var $this yii\web\View */
/* @var $user frontend\models\User */
/* @var $currentUser frontend\models\User */
/* @var $modelPicture frontend\modules\user\models\forms\PictureForm */
/* @var $post frontend\models\Post */

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use dosamigos\fileupload\FileUpload;

$this->title = Html::encode($user->username);
?>

<div class="container full">
    <div class="page-posts no-padding">
        <div class="row">
            <div class="page page-post col-sm-12 col-xs-12 post-82">
                <div class="blog-posts blog-posts-large">
                    <div class="row">

                        <!-- profile -->
                        <article class="profile col-sm-12 col-xs-12">                                            
                            <div class="profile-title">
                                <img src="<?php echo $user->getPicture(); ?>" id="profile-picture" class="author-image" />
                                <div class="author-name"><?php echo Html::encode($user->username); ?></div>

                                <?php if ($currentUser && $currentUser->equals($user)): ?>
                                    <?=
                                    FileUpload::widget([
                                        'model' => $modelPicture,
                                        'attribute' => 'picture',
                                        'url' => ['/user/profile/upload-picture'], // your url
                                        'options' => ['accept' => 'image/*'],
                                        'clientEvents' => [
                                            'fileuploaddone' => 'function(e, data) {
                                    if (data.result.success) {
                                        $("#profile-image-success").show();
                                        $("#profile-image-fail").hide();
                                        $("#profile-picture").attr("src", data.result.pictureUri);
                                    } else {
                                        $("#profile-image-fail").html(data.result.errors.picture).show();
                                        $("#profile-image-success").hide();
                                    }
                                }',
                                        ],
                                    ]);
                                    ?>
                                    <a href="<?php echo Url::to(['/user/profile/update', 'id' => $user->getId()]); ?>" class="btn btn-default"><?php echo Yii::t('view', 'Edit profile'); ?></a>
                                <?php endif; ?>

                                <div class="alert alert-success display-none" id="profile-image-success"><?php echo Yii::t('view', 'Profile image updated') ?></div>
                                <div class="alert alert-danger display-none padding-top-20" id="profile-image-fail"></div>

                            </div>
                            <div class="profile-description">
                                <p><?php echo HtmlPurifier::process($user->about); ?></p>
                            </div>
                            <?php if ($currentUser && !$currentUser->equals($user)): ?>
                                <?php if ($currentUser->checkSubscription($user)): ?>
                                    <a href="<?php echo Url::to(['/user/profile/unsubscribe', 'id' => $user->getId()]); ?>" class="btn btn-danger"><?php echo Yii::t('view', 'Unsubscribe') ?></a>
                                <?php else: ?>
                                    <a href="<?php echo Url::to(['/user/profile/subscribe', 'id' => $user->getId()]); ?>" class="btn btn-success"><?php echo Yii::t('view', 'Subscribe') ?></a>
                                <?php endif; ?>
                                <?php foreach ($currentUser->getMutualSubscriptionsTo($user) as $item): ?>
                                    <hr>
                                    <h5><?php echo Yii::t('view', 'Friends, who are also following {username}', ['username' => Html::encode($user->username)]); ?> :</h5>
                                    <a href="<?php echo Url::to(['/user/profile/view', 'nickname' => ($item['nickname']) ? $item['nickname'] : $item['id']]); ?>">
                                        <?php echo Html::encode($item['username']); ?>
                                    </a>
                                <?php endforeach; ?>
                                <hr>
                            <?php endif; ?>

                            <div class="profile-bottom">
                                <div class="profile-post-count">
                                    <span><?php echo $user->countPosts(); ?> <?php echo Yii::t('view', 'posts'); ?></span>
                                </div>
                                <div class="profile-followers">
                                    <a href="#" data-toggle="modal" data-target="#Followers"><?php echo $user->countFollowers(); ?> <?php echo Yii::t('view', 'followers'); ?></a>
                                </div>
                                <div class="profile-following">
                                    <a href="#" data-toggle="modal" data-target="#Subscriptions"><?php echo $user->countSubscriptions(); ?> <?php echo Yii::t('view', 'following'); ?></a>    
                                </div>
                            </div>
                        </article>

                        <div class="col-sm-12 col-xs-12">
                            <div class="row profile-posts">
                                <?php foreach ($user->getPosts() as $post): ?>
                                    <div class="col-md-4 profile-post">
                                        <a href="<?php echo Url::to(['/post/default/view', 'id' => $post->id]); ?>">
                                            <img src="<?php echo $post->getImage(); ?>" class="author-image" />
                                        </a>
                                    </div>

                                <?php endforeach; ?>                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Subscriptions-->
<div id="Subscriptions" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Subscriptions</h4>
            </div>
            <div class="modal-body">
                <?php foreach ($user->getSubscriptions() as $subscription): ?>
                    <p><a href="<?php echo Url::to(['/user/profile/view', 'nickname' => ($subscription['nickname']) ? $subscription['nickname'] : $subscription['id']]); ?>">
                            <?php echo Html::encode($subscription['username']); ?>
                        </a></p>
                <?php endforeach; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Followers-->
<div id="Followers" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Followers</h4>
            </div>
            <div class="modal-body">
                <?php foreach ($user->getFollowers() as $follower): ?>
                    <p><a href="<?php echo Url::to(['/user/profile/view', 'nickname' => ($follower['nickname']) ? $follower['nickname'] : $follower['id']]); ?>">
                            <?php echo Html::encode($follower['username']); ?>
                        </a></p>
                <?php endforeach; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>