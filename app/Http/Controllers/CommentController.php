<?php

namespace App\Http\Controllers;

use App\User;
use App\Article;
use App\Comment;
use App\Reading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }
    /**
     * Create new comment to the thread .
     *
     */
    public function create($article_id)
    {
        $article = Article::find($article_id);
        return View('comment/create')->with('article', $article);//
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required',
            'file.imagefile' => 'file|image|mimes:jpeg,png|dimensions:max_width=1200,max_height=1200'
        ]);

        $comment = new Comment;
        $comment->article_id = $request->article_id;
        $comment->message = strip_tags($request->message);
        $comment->user_id = \Auth::user()->id;
        $comment->save();

        if($request->file('imagefile') && $request->file('imagefile')->isValid()) {
            $path = $request->file('imagefile')->storeAs('public/', sprintf("c%08d",$comment->id));
            $comment->has_image = 1;
            $comment->save();
        }

        $article = Article::find($comment->article_id);
        $article->updated_at = date("Y-m-d H:i:s");     // Being commented is 'update' of thread.
        $article->save();
        
        $last_read = Reading::where('user_id','=',\Auth::user()->id)->orderBy('read_at','desc')->first();
        if($last_read->comment_id != 0){
            $base_comment = Comment::find($last_read->comment_id);
            return View('comment/show')->with('comment', $base_comment);
        }
        $article = Article::find($last_read->article_id);
        return View('article/show')->with('article', $article);
    }

    public function show($id)
    {
        $record = Reading::where('comment_id',$id)->where('user_id', \Auth::user()->id)->first();
        if($record == NULL){
            $comment = Comment::find($id);
            $record = new Reading;
            $record->user_id = \Auth::user()->id;
            $record->article_id = $comment->article_id;
            $record->comment_id = $id;
            $record->read_at = time();
        }else{
            $record->read_at = time();
        }
        $record->save();

        $comment = Comment::find($id);
        return View('comment/show')->with('comment', $comment);

    }

}
