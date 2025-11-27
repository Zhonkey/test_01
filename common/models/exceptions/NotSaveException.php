<?php

namespace common\models\exceptions;

use yii\base\Model;

class NotSaveException extends \Exception
{
    public function __construct(private readonly Model $model)
    {
        parent::__construct('Not saved model: ' . json_encode($this->model->errors));
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    /**
     * @return array<string>
     */
    public function getErrors(): array
    {
        $errors = [];

        foreach($this->model->getErrors() as $attribute => $attributeErrors) {
            $errors[$attribute] = implode(', ', $attributeErrors);
        }

        return $errors;
    }
}
