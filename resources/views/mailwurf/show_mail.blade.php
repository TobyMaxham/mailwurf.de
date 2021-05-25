@extends('layouts.base')

@section('container')
    <br>
    <div class="row">
        <div class="col-lg-12">
            Betreff: {{ $mail->subject }} <br>
            Gesendet: {{ $mail->date }} <br>
            Absender: {{ $mail->from_display }} <br>
            Empfänger: {{ $mail->to_display }} <br>
            <a href="{{ route('mail.delete', [$mail->mail_key]) }}" class="mdelete">DELETE MAIL
                <span class="mdelete glyphicon glyphicon-trash"></span>
            </a>
            <br>
            <hr>
            <br>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <hr>
            <code class="language-html" data-lang="html" style="width: 100%" rows="100">
                {!! nl2br($mail->text) !!}
            </code>
        </div>
    </div>

@endsection



@section('footerscript')

    <script>
        {{--$( document ).on( 'click', '.mdelete', function() {--}}
        {{--    $.get('{{ route('mail.delete', [$mail->mail_key]) }}', function(data) {--}}
        {{--        self.close()--}}
        {{--    });--}}
        {{--});--}}
    </script>

@endsection
