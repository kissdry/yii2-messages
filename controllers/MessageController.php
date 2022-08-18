<?php

namespace app\controllers;

use app\models\TotalMessagesForm;
use app\models\UserActivityForm;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\web\Controller;
use yii\web\Response;

class MessageController extends Controller
{
    /**
     * @throws InvalidConfigException
     */
    public function actionTotal()
    {
        $model = new TotalMessagesForm();

        $loadData = [
            $model->formName() => Yii::$app->request->get(),
        ];
        if (!$model->load($loadData) || !$model->validate()) {
            return $this->makeResponse($this->getFirstError($model));
        }

        return $this->makeResponse(null, $model->getTotalMessages());
    }

    public function actionUserActivity()
    {
        $model = new UserActivityForm();

        $loadData = [
            $model->formName() => Yii::$app->request->get(),
        ];
        if (!$model->load($loadData) || !$model->validate()) {
            return $this->makeResponse($this->getFirstError($model));
        }

        return $this->makeResponse(null, $model->getUserActivity());
    }

    protected function makeResponse(?string $error = '', ?array $data = []): Response
    {
        return $this->asJson([
            'error' => $error,
            'data' => $data,
        ]);
    }

    protected function getFirstError(Model $model): ?string
    {
        $errors = $model->getErrors();
        if (empty($errors)) {
            return null;
        }
        $firstAttrErrors = array_shift($errors);
        return $firstAttrErrors[0];
    }
}
