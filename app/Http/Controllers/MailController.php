<?php

namespace App\Http\Controllers;

use App\Models\MailImport;

class MailController extends Controller
{
    public function index()
    {
        return view('mailwurf.mails')->with([
            'items' => auth()->user()->getMailImports(),
        ]);
    }

    public function show(MailImport $mail)
    {
        return view('mailwurf.show_mail')->with(compact('mail'));
    }

    public function delete(MailImport $mail)
    {
        $mail->delete();

        return redirect()->route('mail.index');
    }
}
