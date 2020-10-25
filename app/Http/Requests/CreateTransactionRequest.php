<?php

namespace App\Http\Requests;

use App\Rules\WalletHasEnoughFunds;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->isPhysicalPerson();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'value' => [
                'required',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
                'min:1',
                new WalletHasEnoughFunds(),
            ],
            'payer' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    if ($value != $this->user()->id) {
                        $fail('The selected '.$attribute.' is invalid.');
                    }
                },
            ],
            'payee' => [
                'required',
                'integer',
                Rule::exists('users', 'id')->where(function (Builder $query) {
                    $query->where('id', '!=', $this->user()->id);
                }),
            ],
        ];
    }
}
