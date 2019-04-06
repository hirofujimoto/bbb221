<?php

namespace App\Http\Controllers;

use App\User;
use App\Article;
use App\Comment;
use App\Reading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
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
     * Show the application dashboard.
     *
     */
    public function index()
    {
        $threads = Article::orderBy('updated_at', 'desc')->paginate(15);
        return view('article/index')->with('threads',$threads);
    }

    /**
     * Create new article thread .
     *
     */
    public function create()
    {
        return View('article/create');//
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'message' => 'required',
            'file.imagefile' => 'file|image|mimes:jpeg,png|dimensions:max_width=1200,max_height=1200'
        ]);

        $article = new Article;
        $article->title = $request->title;
        $article->message = strip_tags($request->message);
        $article->user_id = \Auth::user()->id;
        $article->save();

        if($request->file('imagefile') && $request->file('imagefile')->isValid()) {
            $path = $request->file('imagefile')->storeAs('public/', sprintf("a%08d",$article->id));
            $article->has_image = 1;
            $article->save();
        }    
         
        return redirect()->route('article.index');
    }

    public function show($id)
    {
        $record = Reading::where('article_id',$id)->where('user_id', \Auth::user()->id)->first();
        if($record == NULL){
            $record = new Reading;
            $record->user_id = \Auth::user()->id;
            $record->article_id = $id;
            $record->comment_id = 0;
            $record->read_at = time();
        }else{
            $record->read_at = time();
        }
        $record->save();

        $article = Article::find($id);
        return View('article/show')->with('article', $article);

    }

    public function unread($id)
    {
        $last_read = Reading::where('article_id',$id)->where('user_id',\Auth::user()->id)
            ->orderBy('read_at','desc')->first();
        if($last_read == NULL){
            return redirect()->route('article.show',[$id]);
        }
        $unread = Comment::where('article_id',$id)
            ->where('id','>',$last_read->comment_id)->orderBy('updated_at','asc')->first();
        if($unread == NULL){
            return redirect()->route('article.show',[$id]);
        }
        return redirect()->route('comment.show',[$unread->id]);
    }
    

}
