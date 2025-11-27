<?php

namespace api\controllers;

use api\presenters\ExceptionPresenter;
use common\models\exceptions\NotSaveException;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * Displays homepage.
     *
     * @return Response
     */
    public function actionError(ExceptionPresenter $presenter)
    {
        $exception = Yii::$app->getErrorHandler()->exception;

        if (empty($exception)) {
            $exception = new NotFoundHttpException();
        }

        switch ($exception::class) {
            case NotFoundHttpException::class:
                Yii::$app->response->statusCode = 404;
                return $this->asJson([
                    'error' => $presenter->systemError($exception),
                ]);
            case NotSaveException::class:
                Yii::$app->response->statusCode = 400;
                return $this->asJson([
                    'error' => $presenter->validationError($exception),
                ]);
            default:
                Yii::$app->response->statusCode = 500;
                return $this->asJson([
                    'error' => $presenter->systemError($exception),
                ]);
        }
    }
}
