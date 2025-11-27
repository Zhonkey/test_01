<?php

namespace api\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\ErrorAction;
use yii2mod\swagger\OpenAPIRenderer;
use yii2mod\swagger\SwaggerUIRenderer;

/**
 * @SWG\Swagger(
 *     basePath="/",
 *     produces={"application/json"},
 *     consumes={"application/json"},
 *     @SWG\Info(version="1.0", title=" API"),
 * )
 */
class DocsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions(): array
    {
        return [
            'index' => [
                'class' => SwaggerUIRenderer::class,
                'restUrl' => Url::to(['docs/json-schema']),
            ],
            'json-schema' => [
                'class' => OpenAPIRenderer::class,
                'scanDir' => [
                    Yii::getAlias('@api/controllers'),
                    Yii::getAlias('@api/models'),
                ],
            ],
            'error' => [
                'class' => ErrorAction::class,
            ],
        ];
    }
}