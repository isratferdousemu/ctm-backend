<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueDemoGraphicRule implements ValidationRule
{
    protected $id;
    protected $typeId;

    public function __construct($id, $typeId)
    {
        $this->id = $id;
        $this->typeId = $typeId;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
// name_en, name_bn, validate unique with id, type, location_type and deleted_at is null
        $model = 'App\Models\Location';
        $model = new $model;
        $model = $model->where($attribute, $value)
            ->where('type', $this->typeId)
            ->where('deleted_at', null);
        if ($this->id) {
            $model = $model->where('id', '!=', $this->id);
        }
        $model = $model->first();
        if ($model) {
            $fail("The $attribute has already been taken.");
        }

    }
}
