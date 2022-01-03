
@extends('layouts.app')

@section('content')

<div class="container" style="width: auto;max-width: auto;margin-top: 5px;">
        <div class="row" id="r1" style="height: auto;">

        @foreach ($posts as $post)
            @include('partials.post', array(
                'id'           => $post->id,
                'idUtilizador' => $post->idutilizador,
                'conteudo'     => $post->conteudo,
                'anexo'        => $post->anexo
        ))
        @endforeach
@endsection
