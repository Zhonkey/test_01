<?php

namespace common\behaviors;

use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

class DateTImeAttributes extends AttributeBehavior
{
    /**
     * @var array<string> List of field names
     */
    public $fields = [];

    /** @inheritdoc */
    public function events(): array
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => fn () => $this->unserialize(),
            ActiveRecord::EVENT_AFTER_INSERT => fn () => $this->unserialize(),
            ActiveRecord::EVENT_AFTER_UPDATE => fn () => $this->unserialize(),
            ActiveRecord::EVENT_BEFORE_INSERT => fn () => $this->serialize(),
            ActiveRecord::EVENT_BEFORE_UPDATE => fn () => $this->serialize(),
        ];
    }

    /**
     * Convert JSON string to array
     */
    public function unserialize(): void
    {
        foreach ($this->fields as $field) {
            if (!is_null($this->owner->{$field}) && !($this->owner->{$field} instanceof \DateTime)) {
                $newValue = $this->owner->{$field} ? new \DateTime($this->owner->{$field}) : null;

                $this->owner->setOldAttribute($field, $newValue);
                $this->owner->{$field} = $newValue;
            }
        }
    }

    /**
     * Convert array to JSON string
     */
    public function serialize(): void
    {
        foreach ($this->fields as $field) {
            $value = $this->owner->{$field};
            if ($value instanceof \DateTime) {
                $this->owner->{$field} = $value->format('Y-m-d H:i:s');
            } elseif ($value === null) {
                $this->owner->{$field} = null;
            }
        }
    }
}
