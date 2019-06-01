<?php

namespace App\Http\Controllers;

use DB;

use Session;
use App\User;
use App\Article;
use App\Comment;
use App\Reading;
use App\History;
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
        if(Session::has('needle')){

            $needle  = Session::get('needle','');
            $threads = Article::groupBy('articles.id')->select('articles.*')
                ->leftjoin('comments','comments.article_id','=','articles.id')
                ->orwhere('articles.message','like','%'.$needle.'%')
                ->orwhere('comments.message','like','%'.$needle.'%')
                ->orderBy('articles.updated_at', 'desc')->paginate(20);
        }else{
            $threads = Article::orderBy('updated_at', 'desc')->paginate(20);
        }
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
            'message' => 'required|max:32000',
            'imagefile' => 'file|image|mimes:jpeg,png|dimensions:max_width=1200,max_height=1200'
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

        return redirect()->route('article.show',[$article->id]);
    }

    public function show($id)
    {
        $record = Reading::where('article_id',$id)->where('comment_id',0)
            ->where('user_id', \Auth::user()->id)->first();
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
    
    public function lastread($id)
    {
        $last_read = Reading::where('article_id',$id)->where('user_id',\Auth::user()->id)
            ->orderBy('read_at','desc')->first();
        if($last_read == NULL || $last_read->comment_id == 0){
            return redirect()->route('article.show',[$id]);
        }
        return redirect()->route('comment.show',[$last_read->comment_id]);
    }

    public function edit($id)
    {
        $article = Article::find($id);
        return View('article/edit')->with('article',$article);
    }

    public function update(Request $request)
    {
        $request->validate([
            'message' => 'required',
            'file.imagefile' => 'file|image|mimes:jpeg,png|dimensions:max_width=1200,max_height=1200'
        ]);

        $article = Article::find($request->article_id);

        $history = new History;
        $history->article_id = $request->article_id;
        $history->comment_id = 0;
        $history->message = $article->message;
        $history->changed_at = time();

        if($request->image == 'delete' || $request->image == 'change'){    // delete or update image file.
            $history->image_tag = sprintf("_%08x",$history->changed_at);
            $image_name = sprintf("a%08d",$article->id);
            Storage::move('public/'.$image_name, 'public/'.$image_name.$history->image_tag);
        }else{
            $history->image_tag = '';
        }
        $history->save();

        if($request->image == 'delete'){
            $article->has_image = 0;
        }elseif($request->image == 'add'){
            $article->has_image = 1;
        }
        if($article->has_image == 1 && $request->image != 'remain'){
            if($request->file('imagefile') && $request->file('imagefile')->isValid()) {
                $path = $request->file('imagefile')->storeAs('public/', sprintf("a%08d",$article->id));                
            }else{
                $article->has_image = 0;    // error.
            }   
        }            
        $article->message = strip_tags($request->message);
        $article->status += 1;
        $article->save();

        return redirect()->route('article.show',[$article->id]);
    }

    public function squeeze(Request $request)
    {
        $request->validate([
            'needle' => 'max:64',
        ]);
        if($request->release){
            Session::forget('needle');
        }else{
            if(strlen($request->needle)){
                Session::put('needle', $request->needle);
            }else{
                Session::forget('needle');
            }       
        }
        return redirect()->route('article.index');
    }

    public function tree($id)
    {
        $article = Article::find($id);
        $comments = Comment::where('article_id', $id)->where('root_id', 0)->orderBy('created_at','asc')->get();
        $tree = Comment::makeTree($comments);
        
        return View('article/tree')->with('article', $article)->with('tree', $tree);
    }

}
