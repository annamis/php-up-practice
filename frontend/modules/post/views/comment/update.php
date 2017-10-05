<?php
/* @var $this yii\web\View */
/* @var $model frontend\modules\post\models\forms\CommentForm */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<div class="post-comment-update">
        <div class="row">
            <div class="col-md-6">
                <?php $form = ActiveForm::begin(); ?>

                <?php echo $form->field($model, 'content')->textarea(['rows' => 2])->label(''); ?>

                <?php echo Html::submitButton('Update comment', ['class' => 'btn btn-default']); ?>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
</div>