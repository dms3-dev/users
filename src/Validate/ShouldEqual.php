<?php

namespace Mediamouse\Users\Validate;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Livewire\Component;

class ShouldEqual implements ValidationRule
{
    private string $fieldKey;
    private string $fieldName;
    private ?Component $livewire;

    public function __construct(string $fieldKey, string $fieldName = '', ?Component $livewire = null) {
        $this->fieldKey = $fieldKey;
        $this->fieldName = strlen($fieldName) > 0 ? $fieldName : $fieldKey;
        $this->livewire = $livewire;
    }

    public function validate(string $attribute, mixed $value, Closure $fail) : void {
        $fieldKey = $this->fieldKey;
        if(
            ($this->livewire && $this->livewire->$fieldKey != $value) ||
            ($this->livewire == null && request()->input($fieldKey) != $value)
        ) {
            $fail("The Fields `{$this->fieldName}` and `:attribute` don't match");
        }
    }
}
