<?php
/* @var $this yii\web\View */
/* @var $model frontend\models\forms\SearchForm */

use yii\widgets\ActiveForm;
use yii\helpers\Html;
use frontend\helpers\HighlightHelper;
?>
<h1>Results</h1>

<div class="row">
    <div class="col-md-12">
        <?php $form = ActiveForm::begin(); ?>
        <?php echo $form->field($model, 'keyword'); ?>
        <?php echo Html::submitButton('Search', ['class' => 'btn btn_primary']) ?>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <?php if ($results): ?>
            <?php foreach ($results as $item): ?>
                <?php echo HighlightHelper::process($model->keyword, $item['username']); ?> (<?php echo HighlightHelper::process($model->keyword, $item['nickname']); ?>)
                <hr>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
