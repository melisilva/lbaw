<div class="row">
    <div class="col post-scroll">
        <div class="d-flex justify-content-between p-2 px-3 posts" style="height: auto;width: auto;max-width: 100%;flex-direction: column;border: 3px solid rgba(118,118,118,0.16);border-radius: 15px;" data-id="{{$post->id}}">
        @if(Auth::id()!=$idUtilizador)
            <header class="d-flex flex-row align-items-center" style="margin-bottom: 10px;"><a style="color: #0c1618; text-decoration: none" href="{{ route('show_profile', ['id' => $idUtilizador]) }}"><img class="post-user" src="{{asset(App\Models\User::find($idUtilizador)->fotoperfil)}}"></a>
            @else
             <header class="d-flex flex-row align-items-center" style="margin-bottom: 10px;"><img class="post-user" src="{{asset(App\Models\User::find($idUtilizador)->fotoperfil)}}">
             @endif
            @if(Auth::id()!=$idUtilizador)
                <a href="{{ route('show_profile', ['id' => $idUtilizador]) }}" style="text-decoration: none;"><h1 class="post-author" style="vertical-align: middle;margin: 0px;margin-top: -2px; color: #0c1618;"> {{App\Models\User::find($idUtilizador)->nome}}</h1>
            </header></a>
            @else
            <h1 class="post-author" style="vertical-align: middle;margin: 0px;margin-top: -2px;"> {{App\Models\User::find($idUtilizador)->nome}}</h1></header>
            @endif
            @if(!is_null($anexo))
               <img class="img-fluid"src="{{ asset($anexo) }}">
            @endif
            <p class="p-1 px-0">{{ $conteudo }}</p>
            @if(Auth::check())
                <footer>
                <a href="#like" style="margin-right: 15px;"><i class="fa fa-thumbs-up"></i></a>
                <a href="#comment" style="margin-right: 15px;"><i class="fa fa-comment" style="padding: 0px;margin-top: 0px;margin-right: 0px;"></i></a>
                @if(Auth::id()==$idUtilizador)
                    <a style="margin-right: 15px;"><i class="fa fa-pencil" style="padding: 0px;margin-top: 0px;margin-right: 0px;" data-bs-target="#edit-post" data-bs-toggle="modal"></i>@include('partials.edit_post')
                    </a>
                    <a href="#"><i class="fa fa-trash"></i></a>
                
                @endif
                </footer>
            
            @endif
        </div>

    </div>
</div>