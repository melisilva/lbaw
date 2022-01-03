<?php

//https://laravel.com/docs/4.2/queries

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Http\Controllers\Controller;

use App\Models\Administrador;
use App\Models\User;

class AdminController extends Controller
{
    /*Administer User Accounts (search, view, edit, create) 
    Search, view, edit and create user accounts*/

    public function view(){
        //if(!Auth::check()) return redirect('/admin-login');

        $users = User::all();

        //$users = DB::table('utilizador')->select('nome', 'fotoPerfil')->get();

        return view('pages.admin_view_user',['users'=>$users]);
    }

    public function ftsAdminPosts(Request $request){
        $search_text=$request->search_text;
  
        $posts=Publicacao::all()->whereRaw("plainto_tsquery(?) @@ to_tsvector(conteudo)",[$search_text])->simplePaginate(5);
  
        return view('pages.feedaut',['posts'=>$posts]);
    }
  
      public function ftsAdminUserName(Request $request){
        $search_text=$request->search_text;
  
        $users=User::all()->whereRaw("plainto_tsquery(?) @@ to_tsvector(nome)",[$search_text])->simplePaginate(5);
  
        return view('pages.view_user',['users'=>$users]);
      }
  
      public function ftsAdminUserEmail(Request $request){
        $search_text=$request->search_text;
  
        $users=User::all()->whereRaw("plainto_tsquery(?) @@ to_tsvector(email)",[$search_text])->simplePaginate(5);
  
        return view('pages.view_user',['users'=>$users]);
      }
  
      public function ftsAdminUserID(Request $request){
        $search_text=$request->search_text;
  
        $users=User::all()->whereRaw("plainto_tsquery(?) @@ to_tsvector(id)",[$search_text])->simplePaginate(5);
  
        return view('pages.view_user',['users'=>$users]);
      }
  
      public function ftsAdminUserNamePost(Request $request){
         $search_text=$request->search_text;
         $search_text2=$request->search_text2;
  
         $users=User::all()->whereRaw("plainto_tsquery(?) @@ to_tsvector(id)",[$search_text]);
  
         foreach($users as $user){
           $posts=Publicacao::where('idutilizador',$user->id)->whereRaw("plainto_tsquery(?) @@ to_tsvector(conteudo)",[$search_text1])->simplePaginate(5);
         }
         return view('pages.feedaut',['posts'=>$posts]);
      }
  
      public function emsAdminUserid(Request $request)
      {
        $request->validate([
              'query' => 'required|min:10|max:10',
          ]);
  
          $query = $request->input('id') ?? -1;
          $users= User::where('id',$query)->get();
          
  
          return view('pages.view_user',['users'=>$users]);
      }
  
      public function emsAdminUsernome(Request $request)
      {
        $request->validate([
              'query' => 'required|min:10|max:10',
          ]);
  
          $query = $request->input('nome');
          $users= User::where('nome',$query)->get();
          
  
          return view('pages.view_user',['users'=>$users]);
      }
  
      public function emsAdminUseremail(Request $request)
      {
        $request->validate([
              'query' => 'required|min:10|max:10',
          ]);
  
          $query = $request->input('email');
          $users= User::where('email',$query)->get();
          
  
          return view('pages.view_user',['users'=>$users]);
      }
  
      public function emsAdminPostid(Request $request)
      {
        $request->validate([
              'query' => 'required|min:10|max:10',
          ]);
  
          $query = $request->input('id') ?? -1;
          $posts= Publicacao::where('id',$query)->get();
          
  
          return view('pages.feedaut',['posts'=>$posts]);
      }  
      
      public function emsAdminUsernomePost(Request $request)
      {
        $request->validate([
              'query' => 'required|min:10|max:10',
          ]);
  
          $query = $request->input('nome');
          $user= User::where('nome',$query)->select('id')->get();
          $posts=Publicacao::where('idutilizador',$user)->get();
          
  
          return view('pages.feedaut',['posts'=>$posts]);
      }
}
