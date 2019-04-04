<?php

namespace App\Http\Controllers;

use App\User;
use App\Article;
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
        $threads = Article::orderBy('created_at', 'desc')->paginate(15);
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
        $article->message = $request->message;
        $article->user_id = \Auth::user()->id;
        $article->save();

        if($request->file('imagefile')->isValid()) {
            $path = $request->file('imagefile')->storeAs('public/', sprintf("%08d",$article->id));
            $article->has_image = 1;
            $article->save();
        }    
         
        return redirect()->route('article.index');
    }

    public function show($id)
    {
//        $user = User::find(\Auth::user()->id);
//        $user->read_message_at = date("Y-m-d H:i:s");
//        $user->save();
        \Auth::user()->read_message_at = date("Y-m-d H:i:s");
        \Auth::user()->save();

        $article = Article::find($id);
        return View('article/show')->with('article', $article);

    }

}
