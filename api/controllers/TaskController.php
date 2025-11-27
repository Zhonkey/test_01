<?php

namespace api\controllers;

use api\presenters\TaskPresenter;
use common\models\enum\TaskStatus;
use common\repositories\TaskRepository;
use common\services\TaskService;
use Yii;
use yii\filters\Cors;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class TaskController extends Controller
{
    const FIVE_MINUTES_CACHE_DURATION = 5 * 60;
    const CACHE_KEY = 'tasksList';

    public $enableCsrfValidation = false;

    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'corsFilter'  => [
                'class' => Cors::class,
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Origin' => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                    'Access-Control-Request-Headers' => ['*'],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['POST'],
                    'updateStatus' => ['PUT'],
                    'delete' => ['DELETE'],
                    'view' => ['GET'],
                    'list' => ['GET'],
                ]
            ]
        ]);
    }

    /**
     * @SWG\Post(
     *     path="/task/create",
     *     tags={"Task"},
     *     summary="Create task.",
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="title", type="string"),
     *             @SWG\Property(property="description", type="string"),
     *         )
     *     ),
     *     @SWG\Response(
     *         response = 200,
     *         description = "Return task view",
     *         @SWG\Schema(
     *             @SWG\Property(property="tasks", type="array", @SWG\Items(ref="#/definitions/TaskResponse")),
     *         )
     *     ),
     *     @SWG\Response(
     *        response = 400,
     *        description = "Validation error",
     *        @SWG\Schema(
     *            @SWG\Property(property="error", ref="#/definitions/ErrorResponse"),
     *        )
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "System error",
     *         @SWG\Schema(
     *             @SWG\Property(property="error", ref="#/definitions/ErrorResponse"),
     *         )
     *     ),
     * )
     */
    public function actionCreate(TaskService $taskService, TaskPresenter $taskPresenter)
    {
        $task = $taskService->create(
            Yii::$app->request->post('title'),
            Yii::$app->request->post('description')
        );

        Yii::$app->cache->delete(self::CACHE_KEY);

        return $this->asJson([
            'task' => $taskPresenter->view($task),
        ]);
    }

    /**
     * View task by id
     *
     * @SWG\Get(
     *     path="/task/{id}",
     *     summary="View task by id",
     *     tags={"Task"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         type="integer",
     *         description="Task id"
     *     ),
     *     @SWG\Response(
     *         response = 200,
     *         description = "Return task view",
     *         @SWG\Schema(
     *             @SWG\Property(property="task", ref="#/definitions/TaskResponse"),
     *         )
     *     ),
     *     @SWG\Response(
     *         response = 400,
     *         description = "Validation error",
     *         @SWG\Schema(
     *             @SWG\Property(property="error", ref="#/definitions/ErrorResponse"),
     *         )
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "System error",
     *         @SWG\Schema(
     *             @SWG\Property(property="error", ref="#/definitions/ErrorResponse"),
     *         )
     *     ),
     * )
     */
    public function actionView($id, TaskRepository $taskRepository, TaskPresenter $taskPresenter)
    {
        $task = $taskRepository->findOne($id);

        if(empty($task)) {
            throw new NotFoundHttpException();
        }

        return [
            'task' => $taskPresenter->view($task),
        ];
    }

    /**
     * Update task status
     *
     * @SWG\Put(
     *     path="/task/{id}/status",
     *     summary="Update task status",
     *     tags={"Task"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Task id",
     *         type="integer",
     *         @SWG\Schema(type="integer", example=1)
     *     ),
     *     @SWG\Parameter(
     *         name="body",
     *         in="body",
     *         required=true,
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="status", type="integer"),
     *         )
     *     ),
     *     @SWG\Response(
     *         response = 200,
     *         description = "Update task status",
     *         @SWG\Schema(
     *             @SWG\Property(property="task", ref="#/definitions/TaskResponse"),
     *         )
     *     ),
     *     @SWG\Response(
     *         response = 400,
     *         description = "Validation error",
     *         @SWG\Schema(
     *             @SWG\Property(property="error", ref="#/definitions/ErrorResponse"),
     *         )
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "System error",
     *         @SWG\Schema(
     *             @SWG\Property(property="error", ref="#/definitions/ErrorResponse"),
     *         )
     *     ),
     * )
     */
    public function actionUpdateStatus($id, TaskRepository $taskRepository, TaskService $taskService, TaskPresenter $taskPresenter)
    {
        $task = $taskRepository->findOne($id);

        if(empty($task)) {
            throw new NotFoundHttpException();
        }

        $taskService->setStatus($task, Yii::$app->request->post('status'));

        Yii::$app->cache->delete(self::CACHE_KEY);

        return $this->asJson([
            'success' => $task->id,
            'task' => $taskPresenter->view($task),
            'errors' => []
        ]);
    }

    /**
     * Delete task
     *
     * @SWG\Delete(
     *     path="/task/{id}",
     *     summary="Delete task",
     *     tags={"Task"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Task Id",
     *         type="integer",
     *         @SWG\Schema(type="integer", example=1)
     *     ),
     *     @SWG\Response(
     *         response = 200,
     *         description = "Delete task",
     *         @SWG\Schema(
     *         )
     *     ),
     *     @SWG\Response(
     *         response = 400,
     *         description = "Validation error",
     *         @SWG\Schema(
     *             @SWG\Property(property="error", ref="#/definitions/ErrorResponse"),
     *         )
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "System error",
     *         @SWG\Schema(
     *             @SWG\Property(property="error", ref="#/definitions/ErrorResponse"),
     *         )
     *     ),
     * )
     */
    public function actionDelete($id, TaskRepository $taskRepository, TaskService $taskService)
    {
        $task = $taskRepository->findOne($id);

        if(empty($task)) {
            throw new NotFoundHttpException();
        }

        $taskService->delete($task);
        Yii::$app->cache->delete(self::CACHE_KEY);

        return $this->asJson([]);
    }

    /**
     * Show tasks list
     *
     * @SWG\Get(
     *     path="/task/list",
     *     summary="Show tasks list",
     *     tags={"Task"},
     *     @SWG\Response(
     *         response = 200,
     *         description = "Show tasks list",
     *         @SWG\Schema(
     *             @SWG\Property(property="tasks", type="array", @SWG\Items(ref="#/definitions/TaskResponse")),
     *         )
     *     ),
     *     @SWG\Response(
     *         response = 400,
     *         description = "Validation error",
     *         @SWG\Schema(
     *             @SWG\Property(property="error", ref="#/definitions/ErrorResponse"),
     *         )
     *     ),
     *     @SWG\Response(
     *         response = 500,
     *         description = "System error",
     *         @SWG\Schema(
     *             @SWG\Property(property="error", ref="#/definitions/ErrorResponse"),
     *         )
     *     ),
     * )
     */
    public function actionList(TaskRepository $taskRepository, TaskPresenter $taskPresenter)
    {
        return $this->asJson(
            Yii::$app->cache->getOrSet(self::CACHE_KEY, function () use($taskPresenter, $taskRepository) {
                return [
                    'tasks' => $taskPresenter->list($taskRepository->findAll()),
                ];
            }, self::FIVE_MINUTES_CACHE_DURATION)
        );
    }
}