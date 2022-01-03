<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Publicacao;
use App\Models\Gosto;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function create($id_post){
        if(!Auth::check()) return redirect('/login');
        $like = new Gosto();
        $post = Publicacao::find($id_post);

        $this->authorize('create', [$like, $post]);

        $like->idPublicacao = $id_post;

        $like->save();

        return $like;
    }

    public function delete($id_post){
        if(!Auth::check()) return redirect('/login');
        $like = DB::table('gosto')->where('idPublicacao','=',$id_post)->where('idutilizador','=',Auth::user())->get();

        $this->authorize('delete', $like);

        $likeObject = $like;

        DB::table('gosto')->where('idPublicacao','=',$id_post)->where('idutilizador','=',Auth::user())->delete();

        return $likeObject;
    }
}