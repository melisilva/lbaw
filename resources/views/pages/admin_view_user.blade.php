@extends('layouts.admin')

@section('content')

@foreach($users as $user)
    <div class="card-group">
        <div class="card">
            <div class="card-header" style="padding: 5px;margin-bottom: 1px;">
                <header class="d-flex flex-row align-items-center">
                   <a href="{{ route('admin_show_profile', ['id' => $user->id]) }}"> <img class="post-user" src="{{ asset($user->fotoperfil) }}"> </a>
                    <a href="{{ route('admin_show_profile', ['id' => $user->id]) }}"><h1 class="post-author" style="vertical-align: middle;margin: 0px;margin-top: -2px;">
                       <span>{{ $user->nome }}</span>
                    </h1></a>
                </header>
            </div><span style="margin-left: 6px;width: 43px;"><a class="admin-links" href="#" style="margin-right: 15px;"><i class="fa fa-exclamation-circle"></i></a><a class="admin-links" href="#"><i class="fa fa-ban"></i></a></span>
        </div>
    </div>
@endforeach
@endsection