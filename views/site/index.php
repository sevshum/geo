<?php

/* @var $this yii\web\View */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields:</p>

    <?php $form = ActiveForm::begin([
        'id' => 'geo-form',
        'method' => 'get',
        'action' => Url::to(['site/index']),
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-11\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>
    <div class="row">
        <div class="col-md-9">
            <?= $form->field($model, 'address')->textInput(['autofocus' => true]) ?>
        </div>
        <div class="col-md-3">
            <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>


    <?php ActiveForm::end(); ?>

    <div class="col-lg-offset-1" style="color:#999;">
        <?php if ($res) : ?>
            <ul>
                <?php foreach ($res as $item) : ?>
                    <li>
                        <a class="js-more-info"
                           data-lat="<?= Html::encode($item['lat']) ?>"
                           data-lng="<?= Html::encode($item['lng']) ?>"
                           href="#">
                            <?= Html::encode($item['address']) . Yii::t('app', ' (Click for more info)') ?>
                        </a>
                        <br>
                        <span class="loader">Loading...</span>
                        <span class="js-info hidden">
                            <span class="metro"></span><br>
                            <span class="district"></span><br>
                            <span class="street"></span><br>
                            <span class="house"></span><br>
                        </span>
                        <br>
                    </li>
                <?php endforeach; ?>
            </ul>

        <?php else : ?>
            <?= Yii::t('app', 'Not found') ?>
        <?php endif; ?>
    </div>


</div>

</div>

<?php  $this->registerJs("

    $('.js-more-info').on('click', function(e){
        function fillInInfo(data, className, name) {
            var str = data.join('<br>&nbsp; &nbsp; &nbsp;');
            spanWrap.find(className).append('<strong>' + name  + '</strong><br> &nbsp; &nbsp; &nbsp;' + str);
        }
        var self = $(this);
        var spanWrap = self.closest('li').find('.js-info');
        var loader = self.closest('li').find('.loader');
        loader.show();
        spanWrap.find('.metro').html('');
        spanWrap.find('.district').html('');
        spanWrap.find('.street').html('');
        spanWrap.find('.house').html('');
        e.preventDefault();
         $.get('" . Url::to(['/site/get-info']) ."', {lng: self.data('lng'), lat: self.data('lat')}, function(data) {
                    fillInInfo(data.metro, '.metro', 'Метро:');
                    fillInInfo(data.district, '.district', 'Район:');
                    fillInInfo(data.street, '.street', 'Улица:');
                    fillInInfo(data.house, '.house', 'Дом:');
                    loader.hide();
                    spanWrap.removeClass('hidden');
                }, 'json');
    });
");
