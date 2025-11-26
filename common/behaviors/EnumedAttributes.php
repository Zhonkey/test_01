<?php

namespace common\behaviors;

use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;

class EnumedAttributes extends AttributeBehavior
{
    /**
     * @var array<string> List of encrypted field names
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
        foreach ($this->fields as $field => $class) {
            if (!is_null($this->owner->{$field}) && !($this->owner->{$field} instanceof $class)) {
                $newValue = $this->owner->{$field} ? $class::from($this->owner->{$field}) : null;

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
        //enum serialized by default before save
    }
}
