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
                'string',
                fn($attribute, $value, $fail) => $this->checkUnique($attribute, $value, $fail),
            ],
        ];
    }

    public function getMail()
    {
        $mail = Str::lower($this->get('email') . '@' . config('mailwurf.main.domain'));
        $mail = str_replace([' '], '', $mail);

        return $mail;
    }

    private function checkUnique($attribute, $value, $fail)
    {
        $mail = $this->getMail();

        if(!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $fail("The mail {$mail} is not a valid address.");
        }

        if(Account::where('mail', $mail)->exists()) {
            $fail("The mail {$mail} already exists.");
        }
    }
}
