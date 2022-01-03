<?php

//https://stackoverflow.com/questions/59230488/how-to-redirect-in-laravel-form-on-post-request
//https://laraveldaily.com/all-about-redirects-in-laravel-5/

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\Publicacao;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PostController extends Controller 
{
    
    
  /**
   * Creates a new post.
   *
   * @return Response
   */
  public function create(Request $request) 
  {
      
      if(!Auth::check()) return redirect('/login');
      $post=new Publicacao();
      $post->numerogostos=0;
      $post->numerocomentarios=0;
      $post->conteudo=$request['body'];
      //$post->anexo=$request['annex'];
      $post->tipoanexo='NULL';
      $post->idutilizador= Auth::id();
      $post->privacidade='public';
      $post->save();
      $post->member = Auth::user();
      return $post;
  } 
    
    public function updatePost($id){
     if(!Auth::check()) return redirect('/login');
       $post=Publicacao::find($id);
       if(is_null($post))
           return view('layouts.error204');
       return view('partials.edit_post',['post' => $post]);
    }
    
    public function editPost(Request $request, $id){
        if(!Auth::check()) return redirect('/login');
        $post=Publicacao::find($id);
        if(is_null($post))
           return view('layouts.error204');
        if(!is_null($request['body'])){
           $post->conteudo=$request['body'];
        }
        
        $post->save();        
        
        return $post;
    }

    /**
     * Deletes a post from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function delete($id, Request $request) //followed Item and CardController
    {
        if(!Auth::check()) return redirect('/login');
        $post = Publicacao::find($id);
        if(is_null($post))
           return view('layouts.error204');
        $post->delete();

        return $post;
    }

    /*
    Don't think we will need these two functions but won't delete them for now
    I say this because you can access the database attributes without get functions (see teacher's example)
    */

    public function getNumberLikes(int $id_post) {
        $post = Publicacao::find($id_post);
        return $post->numeroGostos;
    }

    public function getNumberComments(int $id_post) {
        $post = Publicacao::find($id_post);
        return $post->numeroComentarios;
    }    
}