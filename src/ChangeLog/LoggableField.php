<?php

namespace Mediamouse\Users\ChangeLog;

use Illuminate\Support\Str;
use Mediamouse\Laravel\Models\Model;
use Mediamouse\Users\Models\Contracts\IsLoggable;
use Closure;

class LoggableField
{
    private string $label;
    private array $fields;
    private string|null $template = null;

    private Closure $action;

    public function __construct()
    {
        $this->action = function(IsLoggable $record, LoggableField $field, array $originals, array $attributes) {
            $field->logOnModel($record);
        };
    }

    public function logOnModel(Model $record, array $originals = null, array $attributes = null) {
        if($attributes === null) $attributes = $record->getAttributes();
        if($originals === null) $originals = $record->getOriginals();

        $record->addLog(
            $this->getLabel(),
            $this->formatValue($originals),
            $this->formatValue($attributes)
        );
    }

    public static function make(array|string $fields, string $label = null, $template = null) {
        return (new self())
                ->fields($fields)
                ->label($label)
                ->template($template);
    }

    public function fields(array|string $fields) {
        $this->fields = is_array($fields) ? $fields : [$fields];
        return $this;
    }

    public function template(string|null $template) {
        $this->template = $template;
        return $this;
    }

    public function label(string|null $label) {
        $this->label = $label ?? ucfirst(reset($this->fields));
        return $this;
    }

    public function getLabel() : string {
        return $this->label;
    }

    public function action(Closure $action) {
        $this->action = $action;
        return $this;
    }

    public function hasField($field) {
        return in_array($field, $this->fields);
    }

    public function formatValue(array $data) {
        $replaces = $this->makeReplaces($data);
        return str_replace(array_keys($replaces), $replaces, $this->makeTemplate());
    }

    public function log(IsLoggable $record, array $originals, array $attributes) {
        return app()->call(
            $this->action,
            [
                'record' => $record,
                'field' => $this,
                'originals' => $originals,
                'attributes' => $attributes,
            ],
        );
    }

    private function makeReplaces(array $data = [], $validate = true) {
        $shouldReplace = [];
        foreach($this->fields as $field) {
            if(isset($data[$field])) {
                $shouldReplace[':' . $field] = $data[$field];
            }
            else {
                $shouldReplace[':' . $field] = '';
            }
        }
        return $shouldReplace;
    }

    private function makeTemplate() {
        if($this->template !== null) return $this->template;

        return implode(' ', array_keys($this->makeReplaces(validate: false)));
    }

}
