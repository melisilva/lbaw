@extends('layouts.admin')

@section('content')
<section class="login-dark" style="filter: invert(0%);">
<form method="POST" action="{{ route('admin.login') }}" id="admin-login" style="background: #efc11a;width: 346px;padding: 20px;">
    {{ csrf_field() }}

    <h2 class="visually-hidden">Login Form</h2>
    <div class="illustration"><i class="icon ion-ios-locked-outline" style="border-color: #efc11a; color: #0c1618;"></i></div>
    <div class="mb-3"><input id="email" class="form-control login-input" type="email" name="email" placeholder="Email" value="{{ old('email') }}" style="color: #0c1618;" required autofocus></div>
    @if ($errors->has('email'))
        <span class="error">
          {{ $errors->first('email') }}
        </span>
    @endif

    <div class="mb-3"><input id="password" class="form-control login-input" type="password" name="password" placeholder="Password" style="color: #0c1618;" required></div>
    @if ($errors->has('password'))
        <span class="error">
            {{ $errors->first('password') }}
        </span>
    @endif

    {{-- 
    <label>
        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
    </label>
    --}}

    <div class="mb-3"><button class="btn btn-primary d-block btn btn-primary" id="login-form-button" type="submit" style="color: #efc11a;background: #0c1618;">
        <a href="" style="color: rgb(255,255,255); text-decoration: none;">Iniciar Sessão</a></button></div><a class="forgot" href="#" style="color: #0c1618;">Esqueceste-te da palavra-passe?</a><a class="forgot"  href="{{ route('login') }}" style="color: #0c1618;">Autenticação de Utilizadores</a>
</form>
</section>
@endsection
