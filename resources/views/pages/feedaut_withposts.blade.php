@extends('layouts.app')

@section('content')

<div class="container" style="width: auto;max-width: auto;margin-top: 5px;">
        <div class="row" id="r1" style="height: auto;">
        <div class="col-md-1 offset-md-0 offset-xxl-0" id="sidebar" style="height: auto;min-width: 25%;width: 25%;padding: 0px;"><button class="btn btn-primary btn" id="colegas" type="button">Os Meus Colegas</button><button class="btn btn-primary btn" id="grupos" type="button">Os Meus Grupos</button><button class="btn btn-primary btn" id="grupo-1" type="button">Grupo 1</button><button class="btn btn-primary btn" id="grupo-2" type="button">Grupo 2</button><button class="btn btn-primary btn" id="notificacoes" type="button">Notificações</button></div>
         <div class="col-xl-1 col-xxl-12 offset-xxl-0" style="margin: 0px;margin-left: 7.5px;height: inherit;margin-top: 0px;width: 74%;padding: 0px;max-width: inherit;min-width: auto;max-height: inherit;">
        @foreach ($posts as $post)
            @include('partials.post', array(
                'id'           => $post->id,
                'idUtilizador' => $post->idutilizador,
                'conteudo'     => $post->conteudo,
                'anexo'        => $post->anexo
        ))
        @endforeach
@endsection