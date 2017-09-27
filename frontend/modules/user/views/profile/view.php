<?php
/* @var $this yii\web\View */
/* @var $user frontend\models\User */
/* @var $currentUser frontend\models\User */
/* $var $modelPicture frontend\modules\user\models\forms\PictureForm */

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use dosamigos\fileupload\FileUpload;
?>
<h3><?php echo Html::encode($user->username); ?></h3>
<p><?php echo HtmlPurifier::process($user->about); ?></p>
<hr>

<img src="<?php echo $user->getPicture(); ?>" />

<?= FileUpload::widget([
    'model' => $modelPicture,
    'attribute' => 'picture',
    'url' => ['/user/profile/upload-picture'], // your url, this is just for demo purposes,
    'options' => ['accept' => 'image/*'],
    'clientOptions' => [
        'maxFileSize' => 2000000
    ],
    // Also, you can specify jQuery-File-Upload events
    // see: https://github.com/blueimp/jQuery-File-Upload/wiki/Options#processing-callback-options
    'clientEvents' => [
        'fileuploaddone' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
        'fileuploadfail' => 'function(e, data) {
                                console.log(e);
                                console.log(data);
                            }',
    ],
]); ?>

<?php if ($currentUser && $currentUser->id !== $user->id): ?>
    <?php if (!$currentUser->checkSubscription($user)): ?>
        <a href="<?php echo Url::to(['/user/profile/subscribe', 'id' => $user->getId()]); ?>" class="btn btn-success">Subscribe</a>
        <hr>
    <?php else: ?>
        <a href="<?php echo Url::to(['/user/profile/unsubscribe', 'id' => $user->getId()]); ?>" class="btn btn-danger">Unsubscribe</a>
        <hr>
    <?php endif; ?>
<?php endif; ?>

<button type="button" class="btn btn-info" data-toggle="modal" data-target="#Subscriptions"><?php echo $user->countSubscriptions(); ?> subscriptions</button>
<button type="button" class="btn btn-info" data-toggle="modal" data-target="#Followers"><?php echo $user->countFollowers(); ?> followers</button>

<?php if ($currentUser && $currentUser->id): ?>
    <h5>Friends, who are also following <i> <?php echo Html::encode($user->username) ?></i></h5>
    <div class="row">
        <?php foreach ($currentUser->getMutualSubscriptionsTo($user) as $item): ?>
            <div class="col-md-12">
                <p><a href="<?php echo Url::to(['/user/profile/view', 'nickname' => ($item['nickname']) ? $item['nickname'] : $item['id']]); ?>">
                    <?php echo Html::encode($item['nickname']); ?>
                </a></p>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Modal -->
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

<!-- Modal -->
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