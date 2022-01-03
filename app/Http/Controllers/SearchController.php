<? php

//https://laraveldaily.com/all-about-redirects-in-laravel-5/
//https://stackoverflow.com/questions/62627035/laravel-search-only-exact-match

namespace App\Http\Controllers;

use App\Repositories\PostRepository;
use App\Http\Controllers;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Response;

use App\Models\Publicacao;
use App\Models\User;

class SearchController extends Controller 
{
    public function ftsPosts(Request $request){
      $search_text=$request->search_text;

      $posts=Publicacao::all()->whereRaw("plainto_tsquery(?) @@ to_tsvector(conteudo)",[$search_text])->simplePaginate(5);

      if(Auth::check()){
        return view('pages.feedaut',['posts'=>$posts]);
      }
      else{
        return view('pages.feedvis',['posts'=>$posts]);
      }
    }

    public function ftsUserName(Request $request){
      $search_text=$request->search_text;

      $users=User::all()->whereRaw("plainto_tsquery(?) @@ to_tsvector(nome)",[$search_text])->simplePaginate(5);

      return view('pages.view_user',['users'=>$users]);
    }

    public function ftsUserNamePost(Request $request){ //can only be used by aut
       $search_text=$request->search_text;
       $search_text2=$request->search_text2;

       $users=User::all()->whereRaw("plainto_tsquery(?) @@ to_tsvector(id)",[$search_text]);

       foreach($users as $user){
         $posts=Publicacao::where('idutilizador',$user->id)->whereRaw("plainto_tsquery(?) @@ to_tsvector(conteudo)",[$search_text1])->simplePaginate(5);
       }
       if(Auth::check()){
        return view('pages.feedaut',['posts'=>$posts]);
      }
    }

    public function emsUsernome(Request $request)
    {
      $request->validate([
            'query' => 'required|min:10|max:10',
        ]);

        $query = $request->input('nome');
        $users= User::where('nome',$query)->get();
        

        return view('pages.view_user',['users'=>$users]);
    }

    public function emsPostid(Request $request) //Don't think we will use this
    {
      $request->validate([
            'query' => 'required|min:10|max:10',
        ]);

        $query = $request->input('id') ?? -1;
        $posts= Publicacao::where('id',$query)->get();
        

        if(Auth::check()){
          return view('pages.feedaut',['posts'=>$posts]);
        }
    }  
    
    public function emsUsernomePost(Request $request) //can only be used by aut
    {
      $request->validate([
            'query' => 'required|min:10|max:10',
        ]);

        $query = $request->input('nome');
        $user= User::where('nome',$query)->select('id')->get();
        $posts=Publicacao::where('idutilizador',$user)->get();
        

        if(Auth::check()){
          return view('pages.feedaut',['posts'=>$posts]);
        }
    }
}