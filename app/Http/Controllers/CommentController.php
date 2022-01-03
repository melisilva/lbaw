<? php

namespace App\Http\Controllers;

use App\Repositories\CommentRepository;
use App\Http\Controllers;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

use App\Models\Publicacao;
use App\Models\User;
use App\Models\Comentario;

class CommentController extends Controller 
{
    private $commentRepository;

    public function __construct(CommentRepository $commentRepo)
    {
        $this->commentRepository = $commentRepo;
    }
   /**
     * Creates a new comment.
     *
     * @return Response
     */
    public function create(Request $request, $id_post) 
    {
      if(!Auth::check()) return redirect('/login');
      $comment = new Comentario();
      $this->authorize('create',$comment);
      $comment->idutilizador = Auth::user();
      $comment->idPublicacao = $id_post;
      $comment->conteudo = $request->conteudo;

      $post = Publicacao::find($id_post);

      $this->authorize('create', [$comment, $post]);

      $comment->save();

      return $comment;
    }

   /**
     * Store a newly created comment in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
       $input = $request->all();

        $comment = $this->CommentRepository->create($input);

        Flash::success('Comment saved successfully.');

        return redirect(route('comment.index'));

    }

    /**
     * Edits a comment.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
      if(!Auth::check()) return redirect('/login');
       $comment = Comentario::find($id);
       $this->authorize('edit',$comment);

        if (empty($comment)) {
            Flash::error('Comment not found');

            return response("Post doesn't exist", Response::HTTP_FORBIDDEN);
        }

        return view('comment.edit')->with('comment', $comment);

    }

   /**
     * Updates an edited comment in storage.
     *
     * @param  int  $id
     * @param  Request request containing the new state
     * @return Response
     */
    public function update(Request $request, $id) 
    { 
      if(!Auth::check()) return redirect('/login');
      $comment = $this->commentRepository->findWithoutFail($id);
      $this->authorize('update',$comment);

        if (empty($comment)) {
            Flash::error('Comment not found');

            return redirect()->back();
        }

        $comment = $this->commentRepository->update($request->all(), $id);

        Flash::success('Comment updated successfully.');

        return redirect()->back();
}

   /**
     * Deletes comment.
     *
     * @param  int  $id
     * @return Response
     */
     public function deleteComment($id, Request $request) {
      if(!Auth::check()) return redirect('/login');
      $comment = Comentario::find($id);
      $this->authorize('delete',$comment);
      $comment->delete();

      return $comment;
    }

    public function show($id){
      $comment = Comentario::find($id);
      $this->authorize('show', $comment);
      return view('pages.comments', ['comment' => $comment]);
  }


}
