<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

$this->title = 'Shaman';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-shaman">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(['id' => 'login-form']) ?>
    
        <?php if ($model->errorMessage): ?>
    <div class="alert alert-danger alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <?= Html::encode($model->errorMessage) ?>
</div>
    <?php endif; ?>
    
    <?php $form = ActiveForm::begin(['options' => ['data-pjax' => true]]); ?>

        <?= $form->field($model, 'email') ?>

        <?= $form->field($model, 'password')->input('password') ?>
    
        <?= $form->field($model, 'curlname') ?>

        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>

    <?php ActiveForm::end(); ?>

    <?php if (!$model->errorMessage): ?>
    <ul>
    <?php if ($model->id): ?>
        <li><label>ID</label>: <?= Html::encode($model->id) ?></li>
    <?php endif; ?>
        
    <?php if ($model->name): ?>
        <li><label>Name</label>: <?= Html::encode($model->name) ?></li>
    <?php endif; ?>

    <?php if ($model->email): ?>
        <li><label>Email</label>: <?= Html::encode($model->email) ?></li>
    <?php endif; ?>

    <?php if ($model->roleName): ?>
        <li><label>Role Name</label>: <?= Html::encode($model->roleName) ?></li>
    <?php endif; ?>
                    
    <?php if ($model->token): ?>
        <li><label>Token</label>: <?= Html::encode($model->token) ?></li>
    <?php endif; ?>
        
    <?php if ($model->secretKey): ?>
        <li><label>Secret Key</label>: <?= Html::encode($model->secretKey) ?></li>
    <?php endif; ?>
    <?php endif; ?>    
    <?php Pjax::end(); ?>
        

    </ul>
</div>
