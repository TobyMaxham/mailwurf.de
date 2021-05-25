<?php /** @var \App\Models\MailImport[] $items */?>

@extends('layouts.base')

@section('container')

    <br>

    <div class="row">
        <div class="col-lg-1 col-lg-offset-11">
            <a href="{{ url('/') }}" class="btn btn-default btn-xs">Startseite</a>
        </div>
    </div>

    <br>

    <div class="jumbotron jumbotron-fluid">
        <div class="container">
            <h1 class="display-4">{{ \Illuminate\Support\Str::lower(auth()->user()->username) }}{{ '@'.config('mailwurf.main.domain') }}</h1>
            <p class="lead">Achtung! Alle E-Mails werden nach 7 Tagen gelöscht.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-1 col-lg-offset-11">
            <button class="refresh btn btn-default" type="button"><span class="glyphicon glyphicon-refresh"></span></button>
        </div>
    </div>

    <div class="row">

        <div class="col-lg-12">

            <table class="table">
                <thead>
                <tr>
                    <th>From</th>
                    <th>To</th>
                    <th>CC</th>
                    <th>BCC</th>
                    <th>Subject</th>
                    <th>Date</th>
                    <th></th>
                </tr>
                </thead>

                <tbody>

                @foreach($items as $item)

                    <tr>
                        <td>{{ $item->from_display }}</td>
                        <td>{{ $item->to_display }}</td>
                        <td>{{ $item->cc_display }}</td>
                        <td>{{ $item->bcc_display }}</td>
                        <td>{{ $item->subject }}</td>
                        <td>{{ $item->date }}</td>
                        <td>
                            <a href="{{ route('mail.show', [$item->mail_key]) }}" target="readmail">
                                <span mid="{{ $item->id }}" class="mread glyphicon glyphicon-envelope"></span>
                            </a>
                            <a href="{{ route('mail.delete', [$item->mail_key]) }}">
                                <span mid="{{ $item->id }}" class="mdelete glyphicon glyphicon-trash"></span>
                            </a>
                        </td>
                    </tr>

                @endforeach

                </tbody>

            </table>

            <div class="row">
                <div class="col-lg-1 col-lg-offset-11">
                    <button class="refresh btn btn-default" type="button"><span class="glyphicon glyphicon-refresh"></span></button>
                </div>
            </div>

        </div>
    </div>
@endsection


@section('footerscript')

    <script>
        $( document ).on( 'click', '.refresh', function() {
            window.location.reload();
        });
    </script>

@endsection
