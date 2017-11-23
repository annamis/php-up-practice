<?php
/* @var $this yii\web\View */

use yii\helpers\Url;

$this->title = 'Complains';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Admin site</h1>
    </div>

    <div class="body-content">

        <div class="row">
            <div class="col-lg-4">
                <h2>Complains</h2>

                <p>Sometimes people post offensive things...</p>

                <p><a class="btn btn-default" href="<?php echo Url::to(['/complaints/manage']); ?>">Manage</a></p>
            </div>
            <?php 
            echo '<pre>';
            var_dump(Yii::$app->user->can('viewComplaintsList'));
            echo '</pre>';
            
            ?>
        </div>
    </div>
</div>
