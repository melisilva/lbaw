<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comentario;
use App\Models\Publicacao

class AuxiliarViewsController extends Controller
{
     
    public function getComment(int $id_comment) {
        $comment = Comentario::find($id_comment);

        return view('partials.posts.comentario', ['comentario' => $comment]);
    }

    public function getPost(int $id_post) {
        $post = Publicacao::find($id_post);

        return view('partials.posts.publicacao', ['publicacao' => $post]);
    }

}
