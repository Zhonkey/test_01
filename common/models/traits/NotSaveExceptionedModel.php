<?php

namespace common\models\traits;

use common\models\exceptions\NotSaveException;
use Throwable;
use yii\db\StaleObjectException;

trait NotSaveExceptionedModel
{
    /**
     * @throws StaleObjectException
     * @throws NotSaveException|Throwable
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        $state = parent::save($runValidation, $attributeNames);

        if (!$state) {
            throw new NotSaveException($this);
        }

        return $state;
    }
}
