@extends('layouts.base')

@section('container')

    <br>

    <div class="row">
        <div class="col-lg-1 col-lg-offset-11">
            <a href="{{ route('logout') }}" class="btn btn-default btn-xs">Logout</a>
        </div>
    </div>

    <br>

    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <h1 class="display-4">Hallo {{ $user->name }}</h1>
            <p>Du hast {{ $user->mail_count ?? 0 }} Nachrichten in Deinem Postfach.</p>
            <p>Du hast {{ $user->accounts->count()+1 }} E-Mail-Adressen.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">

            <table class="table">
                <thead>
                <tr>
                    <th>Typ</th>
                    <th>E-Mail</th>
                    <th>Anzahl</th>
                    <th>Abrufen</th>
                </tr>
                </thead>

                <tbody>
                <tr>
                    <td>Base</td>
                    <td>{{ $user->username.'@'.config('mailwurf.main.domain') }}</td>
                    <td>-</td>
                    <td>-</td>
                </tr>

                @foreach($user->accounts as $account)

                    <tr>
                        <td>Account</td>
                        <td>{{ $account->mail }}</td>
                        <td>{{ $account->count_all }}</td>
                        <td> ><a href="{{ route('mail.index') }}">####</a><</td>
                    </tr>

                @endforeach

                </tbody>

            </table>

        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <form class="form" action="{{ route('account.store') }}" method="post">
                {!! csrf_field() !!}
                <fieldset>

                    <!-- Form Name -->
                    <legend>Neuen Mail-Account</legend>

                    <!-- Text input-->
                    <div class="row">
                        <div class="form-group @error('email') has-error @enderror">
                            <label class="col-md-12 control-label" for="textinput">E-Mail</label>
                            <div class="col-md-4">
                                <div class="input-group">
                                    <input id="mail" required name="email" class="form-control" placeholder="abc" type="text">
                                    <span class="input-group-addon">{{ '@'.config('mailwurf.main.domain') }}</span>
                                </div>
                                @error('email')
                                    <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <br>

                    <!-- Button -->
                    <div class="form-group">
                        <div class="col-md-12">
                            <button id="save" name="save" class="btn btn-primary">Speichern</button>
                        </div>
                    </div>

                </fieldset>
            </form>

        </div>
    </div>


@endsection
