{{--https://www.codewall.co.uk/how-to-show-validation-errors-in-laravel-views/--}}
@extends('layouts.app')

@section('content')
<div class="container">
        <div class="card" style="background: #f7f0f5;">
            <div class="card-header">
                <h4>Registo</h4>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('register') }}">
                @csrf
                    <div class="mb-3">
                       <p style="margin-bottom: 5px;">Introduza um Email</p><input class="form-control login-input" type="email" id="e-mail-input" name="email" placeholder="Email">
                            @if ($errors->has('email'))
                                <span class="error">
                                  {{ $errors->first('email') }}
                                </span>
                            @endif
                    </div>
                    <div class="mb-3" style="margin-bottom: 0px;">
                        <p style="margin-bottom: 5px;">Introduza uma Password</p><input class="form-control login-input" type="password" id="psw-input" name="password" placeholder="Password">
                            @if ($errors->has('password'))
                                <span class="error">
                                    {{ $errors->first('password') }}
                                </span>
                            @endif
                    </div>
                    <div class="mb-3">
                       <p style="margin-bottom: 5px;">Repita a Password</p><input class="form-control login-input" type="password" id="psw-input-confirm" name="password_confirmation" placeholder="Confirmar Password">
                    </div>
                    <div class="mb-3">
                       <p style="margin-bottom: 5px;">Nome Próprio</p><input class="form-control" type="text" id="name-input" name="name" placeholder="Nome">
                            @if ($errors->has('name'))
                                <span class="error">
                                    {{ $errors->first('name') }}
                                </span>
                            @endif
                    </div>
                    <div class="mb-3">
                       <p style="margin-bottom: 5px;">Data de Nascimento</p><input class="form-control" id="date-input" name="birthdate" type="date">
                            @if ($errors->has('birthdate'))
                                <span class="error">
                                    {{ $errors->first('birthdate') }}
                                </span>
                            @endif
                    </div>
                    <div>
                        <p style="margin-bottom: 5px;">Privacidade</p><div id="privacy" class="btn-group" role="group" aria-label="Basic radio toggle button group">
    <input type="radio" class="btn-check btn-secondary" name="public" id="btnradio1" autocomplete="off" data-bs-toggle="tooltip" y-placement="middle" data-bs-placement="left" data-bs-title="Serás visível a todos os utilizadores registados no Noodle!">
    <label class="btn btn-outline-primary" for="btnradio1">Público</label>
  
    <input type="radio" class="btn-check btn-secondary" name="private" id="btnradio2" autocomplete="off" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Serás visível apenas para os teus colegas!">
    <label class="btn btn-outline-primary" for="btnradio2">Privado</label>
 
  </div>
                    </div>
                    <div class="mb-3">
                       <p style="margin-bottom: 5px;">Foto de Perfil</p><input class="form-control" type="file" id="profile-photo-input">
                            @if ($errors->has('profilepic'))
                                <span class="error">
                                    {{ $errors->first('profilepic') }}
                                </span>
                            @endif
                    </div>
                    <div>
                        
  </div>
                    </div>
                    </div><button class="btn btn-primary" id="reg-submit" type="submit">Registar</button>
                </form>
            
            
        </div>
    </div>

@endsection