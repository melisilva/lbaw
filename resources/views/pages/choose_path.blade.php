{{--https://www.codewall.co.uk/how-to-show-validation-errors-in-laravel-views/--}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card" style="background: #f7f0f5;">
        <div class="card-header">
            <h4>Registo</h4>
        </div>
        <div class="card-body">
            <div>
                <p style="margin-bottom: 5px;">Qual destes dois és?</p>
                <div id="user-spec" class="btn-group" role="group" aria-label="Basic radio toggle button group">
                    <input form="student-register" type="radio" class="btn-check" data-bs-toggle="modal" data-bs-target="#estudante" name="btnradio-user-spec" id="btnradio5" autocomplete="on" value="student" checked />
                    <label class="btn btn-outline-primary" for="btnradio5">Estudante</label>
                    @include('partials.create_estudante')
                    </div>
 <div id="user-spec" class="btn-group" role="group" aria-label="Basic radio toggle button group">
                    <input form="teacher-register" type="radio" class="btn-check" data-bs-toggle="modal" data-bs-target="#docente" name="btnradio-user-spec" id="btnradio6" value="teacher" autocomplete="on" />
                    <label class="btn btn-outline-primary" for="btnradio6">Docente</label>
                    @include('partials.create_docente')
</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection