<?php

namespace app\controllers;

use app\models\TotalMessagesForm;
use yii\helpers\Url;
use yii\httpclient\Client;
use Yii;
use yii\base\InvalidRouteException;
use yii\console\Exception;
use yii\web\Controller;

class SiteController extends Controller
{
    /**
     * Displays Homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Displays Total Form.
     *
     * @return string
     * @throws InvalidRouteException|Exception
     */
    public function actionTotalMessagesForm()
    {
        $model = new TotalMessagesForm();

        $data = [];
        if ($model->load(Yii::$app->request->post())) {
            $client = new Client(['baseUrl' => Url::base(true)]);
            $response = $client->createRequest()
                ->setFormat(Client::FORMAT_JSON)
                ->setUrl([
                    'message/total',
                    'period_start' => $model->period_start,
                    'period_end' => $model->period_end,
                    'period_group_unit' => $model->period_group_unit,
                ])
                ->send();
            $response = $response->data;
            if ($response['error']) {
                Yii::$app->session->setFlash('error', $response['error']);
            } else {
                $data = $response['data'];
            }
        }

        return $this->render('total-messages-form', [
            'model' => $model,
            'data' => $data,
        ]);
    }
}
