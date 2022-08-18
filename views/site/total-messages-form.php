<?php

/** @var View $this */
/** @var TotalMessagesForm $model */
/** @var array $data */

use app\models\TotalMessagesForm;
use dosamigos\chartjs\ChartJs;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

$this->title = 'Total Form';

$form = ActiveForm::begin([
    'id' => 'total-messages-form',
    'options' => ['class' => 'form-horizontal'],
]);

?>

<?= $form->field($model, 'period_start') ?>
<?= $form->field($model, 'period_end') ?>
<?= $form->field($model, 'period_group_unit')->dropDownList([
    $model::GROUP_DAY => 'Day',
    $model::GROUP_MONTH => 'Month',
    $model::GROUP_YEAR => 'Year',
]) ?>

    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Show', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

<?php

    ActiveForm::end();

?>

<?php if ($data): ?>

    <?php
        $labels = [];
        $dataset = [];
        foreach ($data as $datum) {
            $labels[] = $datum['period_start'] . ' - ' . $datum['period_end'];
            $dataset[] = $datum['message_number'];
        }
        echo ChartJs::widget([
            'type' => 'bar',
            'options' => [
                'height' => 400,
                'width' => 400
            ],
            'data' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'label' => "Messages Totals",
                        'backgroundColor' => "rgba(19,81,98,0.7)",
                        'borderColor' => "rgba(19,81,98,1)",
                        'pointBackgroundColor' => "rgba(179,181,198,1)",
                        'pointBorderColor' => "#fff",
                        'pointHoverBackgroundColor' => "#fff",
                        'pointHoverBorderColor' => "rgba(179,181,198,1)",
                        'data' => $dataset,
                    ],
                ]
            ]
        ]);
    ?>

<?php endif; ?>
