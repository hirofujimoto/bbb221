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
        
        $last_read = Reading::where('user_id','=',\Auth::user()->id)->last();
        if($last_read->article_id != 0){
            $article = Article::find($last_read->article_id);
            return View('article/show')->with('article', $article);
        }
        $comment = Comment::find($last_read->$comment_id);
        return View('comment/show')->with('comment', $comment);
    }

    public function show($id)
    {
        $record = new Reading;
        $record->user_id = \Auth::user()->id;
        $record->article_id = 0;
        $record->comment_id = $id;
        $record->save();

        $comment = Comment::find($id);
        return View('comment/show')->with('comment', $comment);

    }

}
