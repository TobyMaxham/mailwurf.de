<?php

namespace App\Http\Requests;

use App\Models\Account;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class AccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return null != auth()->user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => [
                'required',
                'alpha_num',
                fn($attribute, $value, $fail) => $this->checkUnique($attribute, $value, $fail),
            ],
        ];
    }

    public function getMail()
    {
        return Str::lower($this->get('email') . '@' . config('mailwurf.main.domain'));
    }

    private function checkUnique($attribute, $value, $fail)
    {
        $mail = $this->getMail();
        if(Account::where('mail', $mail)->exists()) {
            $fail("The mail {$mail} already exists.");
        }
    }
}
