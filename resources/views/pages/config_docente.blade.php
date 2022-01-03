@extends('layouts.app')
@section('content')

<div class="container" style="width: auto;display: flex;padding: 0px;height: auto;">
    <div style="background: #f7f0f5;padding: 10px;">
        <header>
            <h1>Configurações</h1>
        </header>
        <form method="POST" action="{{route('config_user', ['id'=>$user->id])}}" style="width: 700px;margin: 0px;margin-bottom: 10px;">
            @csrf
            <p style="margin-bottom: 5px;">Nome Completo<input class="form-control" name="name" type="text" id="name-input" style="margin-bottom: 10px;" placeholder="{{$user->nome}}"></p>
            <p style="margin-bottom: 5px;">Data de Nascimento<input class="form-control" name="birthdate" id="date-input" type="date"></p>
            <p style="margin-bottom: 5px;">Departamento<input class="form-control" name="department" type="text" id="departament-input" placeholder="{{$departamento}}"></p>
            <p style="margin-bottom: 5px;">Formação<input class="form-control" name="formation" type="text" id="formation-input" placeholder="{{$formacao}}"></p>
            <p style="margin-bottom: 5px;">Foto de Perfil<input class="form-control" name="profile-pic" type="file" id="profile-photo-input"></p>
                <p style="margin-bottom: 5px;">Foto de Capa<input class="form-control" name="header-pic" type="file" id="header-photo-input"></p>
                <div>
                    <p style="margin-bottom: 5px;">Privacidade</p><div id="privacy" class="btn-group" role="group" aria-label="Basic radio toggle button group">
    <input type="radio" class="btn-check btn-secondary" name="public" id="btnradio1" autocomplete="on" data-bs-toggle="tooltip" y-placement="middle" data-bs-placement="left" data-bs-title="Serás visível a todos os utilizadores registados no Noodle!">
    <label class="btn btn-outline-primary" for="btnradio1">Público</label>
  
    <input type="radio" class="btn-check btn-secondary" name="private" id="btnradio2" autocomplete="on" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Serás visível apenas para os teus colegas!">
    <label class="btn btn-outline-primary" for="btnradio2">Privado</label>
 
  </div>
                </div>
                <footer><button class="btn btn-primary" id="save-btn" type="submit" style="background: #efc11a;float: left;">Guardar</button>
                </form>
                <form method="post" action="{{route('delete_user', ['id'=>$user->id])}}">
                @csrf
                @method('delete')
                <button class="btn btn-primary" id="delete-btn" type="submit" style="background: var(--bs-red);float: right;">Apagar Conta</button></footer>
                </form>
            
            
        </div>
</div>
@endsection



              