<?php

namespace common\models;

use common\behaviors\DateTImeAttributes;
use common\behaviors\EnumedAttributes;
use common\models\enum\TaskStatus;
use common\models\traits\NotSaveExceptionedModel;
use DateTime;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "tasks".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property TaskStatus $status
 * @property DateTime|null $created_at
 * @property DateTime|null $updated_at
 */
class Task extends \yii\db\ActiveRecord
{
    use NotSaveExceptionedModel;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tasks';
    }

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                [
                    'class' => TimestampBehavior::class,
                    'value' => new DateTime(),
                ],
                [
                    'class' => EnumedAttributes::class,
                    'fields' => [
                        'status' => TaskStatus::class,
                    ],
                ],
                [
                    'class' => DateTImeAttributes::class,
                    'fields' => ['created_at', 'updated_at'],
                ],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'description'], 'required'],
            [['status'], 'safe'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}
