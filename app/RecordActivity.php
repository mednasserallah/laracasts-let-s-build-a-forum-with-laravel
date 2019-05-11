<?php
/**
 * Created by PhpStorm.
 * User: Nasser-allah
 * Date: 5/3/2019
 * Time: 1:13 PM
 */

namespace App;

use Illuminate\Support\Str;

trait RecordActivity
{
    protected static function bootRecordActivity()
    {
        if (auth()->guest()) return;

        foreach (self::getActivitiesToRecord() as $event) {
            static::$event(function ($model) use ($event) {
                $model->recordActivity($event);
            });
        }

        self::deleting(function ($modal) {
           $modal->activities()->delete();
        });
    }

    /**
     * @param $event
     * @throws \ReflectionException
     */
    function recordActivity($event): void
    {
        $this->activities()->create([
            'type' => $this->getActivityType($event),
            'user_id' => auth()->id(),
        ]);
    }

   protected static function getActivitiesToRecord()
   {
       return ['created'];
   }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    /**
     * @param $event
     * @return string
     * @throws \ReflectionException
     */
    protected function getActivityType($event): string
    {
        $type = Str::lower(class_basename($this));

        return "{$event}_{$type}";
    }
}
