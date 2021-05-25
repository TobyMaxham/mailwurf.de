<?php


namespace App\Http\Controllers;


use App\Http\Requests\AccountRequest;
use App\Models\Account;

class AccountController
{
    public function index()
    {
        $user = auth()->user();
        $user->load('accounts');

        return view('mailwurf.accounts', [
            'user' => $user,
        ]);
    }

    public function store(AccountRequest $request)
    {
        $account = new Account();
        $account->mail = $request->getMail();
        $account->user()->associate(auth()->user());
        $account->save();

        return redirect()->back();
    }
}
