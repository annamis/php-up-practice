<?php
/* @var $this yii\web\View */
/* @var $user frontend\models\User */
/* @var $currentUser frontend\models\User */

use yii\helpers\Html;
use yii\helpers\Url;

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
                            </div>
                            <div class="profile-description">
                                <p>This page was deleted. Information is unavaliable.</p>
                            </div>
                            <?php if ($currentUser->equals($user)): ?>
                            <a href="<?php echo Url::to(['/user/profile/recover', 'nickname' => $user->getNickname()]); ?>" class="btn btn-danger">Recover account</a>
                            <?php endif; ?>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
