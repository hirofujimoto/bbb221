<?php

namespace App\Http\Controllers;

use App\User;
use App\Article;
use App\Comment;
use App\Reading;
use App\History;
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
    public function create($article_id, $comment_id = 0)
    {
        $article = Article::find($article_id);
        if($comment_id == 0){
            return View('comment/create')->with('article', $article);//
        }
        $comment = Comment::find($comment_id);
        return View('comment/create')->with('article', $article)->with('comment', $comment);//
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required',
            'file.imagefile' => 'file|image|mimes:jpeg,png|dimensions:max_width=1200,max_height=1200'
        ]);

        $comment = new Comment;
        $comment->article_id = $request->article_id;
        $comment->root_id = isset($request->root_id) ? $request->root_id:0;
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
    
        return redirect()->route('comment.show',[$comment->id]);

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
        $neighbours = $comment->neighbours();
        return View('comment/show')->with('comment', $comment )->with('neighbours',$neighbours);

    }

    public function edit($id)
    {
        $comment = Comment::find($id);
        return View('comment/edit')->with('comment',$comment);
    }

    public function update(Request $request)
    {
        $request->validate([
            'message' => 'required',
            'file.imagefile' => 'file|image|mimes:jpeg,png|dimensions:max_width=1200,max_height=1200'
        ]);

        $comment = Comment::find($request->comment_id);

        $history = new History;
        $history->article_id = $comment->article_id;
        $history->comment_id = $comment->id;
        $history->message = $comment->message;
        $history->changed_at = time();

        if($request->image == 'delete' || $request->image == 'change'){    // delete or update image file.
            $history->image_tag = sprintf("_%08x",$history->changed_at);
            $image_name = sprintf("c%08d",$comment->id);
            Storage::move('public/'.$image_name, 'public/'.$image_name.$history->image_tag);
        }else{
            $history->image_tag = '';
        }
        $history->save();

        if($request->image == 'delete'){
            $comment->has_image = 0;
        }elseif($request->image == 'add'){
            $comment->has_image = 1;
        }
        if($comment->has_image == 1 && $request->image != 'remain'){
            if($request->file('imagefile') && $request->file('imagefile')->isValid()) {
                $path = $request->file('imagefile')->storeAs('public/', sprintf("c%08d",$comment->id));                
            }else{
                $comment->has_image = 0;    // error.
            }   
        }            
        $comment->message = strip_tags($request->message);
        $comment->status += 1;
        $comment->save();

        return redirect()->route('comment.show',[$comment->id]);
    }
}
