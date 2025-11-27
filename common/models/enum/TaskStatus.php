<?php

namespace common\models\enum;

use Yii;

enum TaskStatus: int
{
    case CREATED = 1;
    case IN_PROCESS = 2;
    case COMPLETED = 3;
}