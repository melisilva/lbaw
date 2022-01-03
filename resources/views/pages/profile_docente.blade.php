@extends('layouts.app')

@section('content')

<body class="min-vh-100, d-flex,flex-column,justify-content-between" style="height: auto;width: auto;display: flex;flex-direction: column;min-height: auto;">
<div class="container" style="width: auto;display: flex;padding: 0px;height: auto;">
    <div class="row" id="r1" style="width: auto;height: auto;">
        <div class="col-xxl-12" style="padding: 0px;width: auto;height: auto;">
            <div class="row" style="width: auto;height: auto;max-height: 450px;margin-bottom: 70px;">
                <div class="col" style="width: auto;height: auto;max-height: 450px;padding: 0px;margin-bottom: 0px;">
                       <img src="{{ asset($headerPic) }}" style="width: auto;height: auto;max-width: 100%;min-width: auto;max-height: 95%;">
                       <img class="profile-pic rounded-circle" src="{{ asset($profilePic) }}" style="width: 200px;height: 200px;">
                       @if(Auth::id()!=$user->id && Auth::check())
                       @if(!is_null($colleagues))
                       @foreach ($colleagues as $friend)
                       @if($friend->utilizador2!=Auth::id())
                       <button class="btn btn-primary btn" id="edit-profile" type="button" data-bs-toggle="modal" data-bs-target="#edit-profile-modal" style="padding: 0px;margin: 0px;position: absolute;top: 465px;left: 70%;">Adicionar Colega
                       </button>
                       @endif
                       @endforeach
                       @else
                       <button class="btn btn-primary btn" id="edit-profile" type="button" data-bs-toggle="modal" data-bs-target="#edit-profile-modal" style="padding: 0px;margin: 0px;position: absolute;top: 465px;left: 70%;">Adicionar Colega
                       </button>
                       @endif
                       @endif
                        <h1 style="width: auto;position: relative;top: -200px;left: 265px;margin: 0px;margin-top: 0px;max-width: 45%;">
                        {{ $user->nome }}
                        </h1>
                       
                </div>
            </div>
            @if($user->privacidade=="private")
            @if(Auth::id()==$user->id)
            @include('partials.profile_info_docente')
            @else
            @if(!is_null($colleagues))
            @foreach ($colleagues as $friend)
            @if($friend->utilizador2==Auth::id())
            @include('partials.profile_info_docente')
            @endif 
            @endforeach
            @endif 
            @endif        
            @else
            @include('partials.profile_info_docente')
                               
            @endif 

               
                
@endsection