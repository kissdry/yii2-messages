<?php

/** @var yii\web\View $this */

use app\models\TotalMessagesForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Messages Application';

$totalMessagesUrl = Url::toRoute([
    'message/total',
    'period_start' => '2020-06-01',
    'period_end' => '2021-05-31',
    'period_group_unit' => TotalMessagesForm::GROUP_MONTH
]);

$userActivityUrl = Url::toRoute([
    'message/user-activity',
    'period_start' => '2020-06-01',
    'period_end' => '2021-05-31',
    'limit' => 5,
    'dir' => 'desc',
]);

$totalFormUrl = Url::toRoute('site/total-messages-form');

?>
<div class="jumbotron text-center bg-transparent">
    <h1 class="display-4">Messages Application</h1>
</div>

<div class="body-content">
    <div class="row">
        <div class="col-lg-4">
            <h2>Total Messages</h2>

            <p><?= Html::a($totalMessagesUrl, $totalMessagesUrl); ?></p>
        </div>

        <div class="col-lg-4">
            <h2>User Activity</h2>

            <p><?= Html::a($userActivityUrl, $userActivityUrl); ?></p>
        </div>

        <div class="col-lg-4">
            <h2>Total Messages Form</h2>

            <p><?= Html::a('HTML Total Messages Form', $totalFormUrl); ?></p>
        </div>
    </div>
</div>
