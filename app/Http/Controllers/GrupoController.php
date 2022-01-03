<?php

//https://laravel.com/docs/8.x/database

namespace App\Http\Controllers;

use App\Models\grupoPublicacao;
use App\Models\Grupo;
use App\Models\Publicacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function show($id) {
        $group = DB::table('grupoPublicacao')->where('idGrupo','=',$id)->get();
        $posts = DB::select('select idPublicacao from grupoPublicacao where idGrupo = ?',$id);

        return view('pages.grupo', ['grupo' => $group, 'publicacao' => $post]);
    }
}
