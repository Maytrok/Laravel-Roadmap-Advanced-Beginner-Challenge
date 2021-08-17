<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class BaseApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Overriding this method prevents redirect
     */
    protected function failedValidation(Validator $validator)
    {
        throw (new ValidationException($validator));
    }

    protected function getPatchRulesFromCreateRequest(BaseApiRequest $request)
    {
        $rules = collect($request->rules());

        $rules = $rules->map(function ($item) {

            $r = [];
            foreach ($item as $value) {
                $r[] = $value == "required" ? "sometimes" : $value;
            }
            return $r;
        });
        return $rules->toArray();
    }
}
