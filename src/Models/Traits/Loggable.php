<?php

namespace Mediamouse\Users\Models\Traits;

use Mediamouse\Users\ChangeLog\LoggableField;
use Mediamouse\Users\Models\ChangeLog;
use Mediamouse\Users\Models\User;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait Loggable
{
    private bool $shouldRegisterLogs = false;

    public function logs(): MorphMany
    {
        return $this->morphMany(ChangeLog::class, 'loggable');
    }

    protected function loggableFields() {
        return [
        ];
    }

    private function addAutoLogs() {
        if(!$this->shouldRegisterLogs) return;
        $logs = $this->loggableFields();
        foreach($this->changes as $key => $new) {
            /** @var LoggableField $field */
            foreach($logs as $logid => $field) {
                if($field->hasField($key)) {
                    $field->log($this, $this->original, $this->attributes);
                    unset($logs[$logid]);
                }
            }
        }

        $this->shouldRegisterLogs = false;
    }

    public static function bootLoggable() {

        static::updating(function($model){
            $model->shouldRegisterLogs = true;
        });

        static::updated(function($model){
            $model->addAutoLogs();
        });
    }

    public static function boot() {
        parent::boot();

        static::bootLoggable();
    }

    public function addLog(
        string $description = null,
        string $oldValue = null,
        string $newValue = null,
        ?User $user = null,
        ?ChangeLog $changeLog = null
    ): static {

        if($changeLog == null) {
            $changeLog = new ChangeLog();
        }

        $changeLog->loggable_type = self::class;
        $changeLog->loggable_id = $this->{$this->primaryKey};

        if($description !== null) $changeLog->description = $description;
        $changeLog->old_value = $oldValue ?? '';
        $changeLog->new_value = $newValue ?? '';

        if($changeLog->created_by === null) {
            if($user !== null) {
                $changeLog->created_by = $user->id;
            }
            elseif(Filament::auth()->user() !== null) {
                $changeLog->created_by = Filament::auth()->user()->id;
            }
            else {
                $changeLog->created_by = User::query()->where('username', 'cron')->value('id');
            }
        }


        $changeLog->save();

        return $this;
    }
}
