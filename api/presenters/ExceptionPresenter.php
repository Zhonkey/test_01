<?php
namespace api\presenters;

use api\models\ErrorResponse;
use common\models\exceptions\NotSaveException;
use Throwable;

class ExceptionPresenter
{
    public function __construct(
        private readonly ErrorResponse $errorResponse,
    ) {
    }

    /**
     * @param Throwable $exception
     * @return array
     */
    public function systemError(Throwable $exception): array {
        return $this->errorResponse->build(
            'System Error',
            [
                $exception->getMessage()
            ]
        );
    }

    /**
     * @param NotSaveException $exception
     * @return array
     */
    public function validationError(NotSaveException $exception):array {
        return $this->errorResponse->build(
            'Validation Error',
            $exception->getModel()->getErrors()
        );
    }
}