<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

class GamePlayRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|alpha_dash|max:10',
            'cards' => 'required|array|min:3,max:13',
            'cards.*' => [
                'required',
                'distinct:strict',
                Rule::in(config('constants.cards')),
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (is_string($this->cards)) {
            $this->merge([
                'cards' => explode(' ', $this->cards),
            ]);
        }
    }

    public function message(): array
    {
        return [
            'cards.array' => 'Invalid input',
        ];
    }


    public function withValidator(Validator $validator)
    {
        if ($validator->fails()) {
            $errorMsgs = collect($validator->errors()->toArray())
                ->mapWithKeys(function ($item, $key) {
                    $key = explode('.', $key)[0];

                    return [$key => $item];
                })->toArray();
            
            $validator->setValueNames([
                'cards.*' => 'opa!',
            ]);

            throw ValidationException::withMessages($errorMsgs);
        }
    }
}
