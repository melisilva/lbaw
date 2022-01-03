<div class="row" style="margin-right: 0px;margin-left: 0px;display: flex;flex-direction: row;height: auto;flex-wrap: nowrap;width: auto;margin-top: 0px;margin-bottom: 15px;">
    <div class="col-xxl-1" style="max-width: inherit;width: 25%;padding: 0px;padding-right: 0px;height: auto;max-height: 355px;margin-right: 7.5px;">
        <div style="width: 100%;margin: 0px;margin-top: 0px;background: #f7f0f5;box-shadow: 0px 0px 20px #0c1618;height: inherit;max-height: 355px;">
            <p style="margin-left: 0px;padding: 10px;margin-bottom: 0px;width: auto;height: inherit;max-height: inherit;">
                <strong>Nome Completo</strong><br>
                {{ $user->nome }}<br><br>
                <strong>Data de Nascimento</strong><br>
                {{ $user->datanascimento }}<br><br>
                <strong>Instituição de Ensino</strong><br>
                {{ $user->instituicaoensino }}<br><br>
                <strong>Departamento</strong><br>
                {{$departamento}}<br><br>
                <strong>Formação</strong><br>
                {{$formacao}}<br>
            </p>
        </div>
    </div>
    <div class="col-xl-1 col-xxl-12 offset-xxl-0" style="margin: 0px;margin-left: 7.5px;height: inherit;margin-top: 0px;width: 74%;padding: 0px;max-width: inherit;min-width: auto;max-height: inherit;">
        @if(Auth::check() && Auth::id()==$user->id)
        @include('partials.create_post_block')
        @endif
        @foreach ($posts as $post)
        @include('partials.post', array(
        'id' => $post->id,
        'idUtilizador' => $post->idutilizador,
        'conteudo' => $post->conteudo,
        'anexo' => $post->anexo
        ))
        @endforeach

        <div class="row">
            <div class="col post-scroll"></div>
        </div>
    </div>