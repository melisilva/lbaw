@extends('layouts.admin')

@section('content')

<div class="container" style="width: auto;display: flex;padding: 0px;height: auto;">
    <div class="row" id="r1" style="width: auto;height: auto;">
        <div class="col-xxl-12" style="padding: 0px;width: auto;height: auto;">
            <div class="row" style="width: auto;height: auto;max-height: 450px;margin-bottom: 70px;">
                <div class="col" style="width: auto;height: auto;max-height: 450px;padding: 0px;margin-bottom: 0px;">
                       <img src="{{ asset($headerPic) }}" style="width: auto;height: auto;max-width: 100%;min-width: auto;max-height: 95%;">
                       <img class="profile-pic rounded-circle" src="{{ asset($profilePic) }}" style="width: 200px;height: 200px;">
                       
                        <h1 style="width: auto;position: relative;top: -200px;left: 265px;margin: 0px;margin-top: 0px;max-width: 45%;">
                        {{ $user->nome }}
                        </h1>
                </div>
            </div>
            <div class="row" style="margin-right: 0px;margin-left: 0px;display: flex;flex-direction: row;height: auto;flex-wrap: nowrap;width: auto;margin-top: 0px;margin-bottom: 15px;">
                    <div class="col-xxl-1" style="max-width: inherit;width: 25%;padding: 0px;padding-right: 0px;height: auto;max-height: 355px;margin-right: 7.5px;">
                        <div style="width: 100%;margin: 0px;margin-top: 0px;background: #f7f0f5;box-shadow: 0px 0px 20px #0c1618;height: inherit;max-height: 355px;">
                            <p style="margin-left: 0px;padding: 10px;margin-bottom: 0px;width: auto;height: inherit;max-height: inherit;">
                            <strong>Nome Completo</strong><br>
                            {{ $user->nome }}<br><br>
                            <strong>Data de Nascimento</strong><br>
                            {{ $user->datanascimento}}<br><br>
                            <strong>Instituição de Ensino</strong><br>
                            {{ $user->instituicaoensino }}<br><br>
                            <strong>Curso</strong><br>
                            {{$curso}}<br><br>
                            <strong>Ano Corrente</strong><br>
                            {{$anoCorrente}}<br></p>                       
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
@endsection