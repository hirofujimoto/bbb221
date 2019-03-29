<?php

namespace App\Http\Controllers;

use App\User;
use App\Article;
use Illuminate\Http\Request;

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
     * Create new article thread .
     *
     */
    public function create()
    {

        return View('article/create');//
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'message' => 'filled',
        ]);

        $article = new Article;
        $article->title = $request->title;
        $article->message = $request->message;
        $article->user_id = \Auth::user()->id;
        $article->save();

        return redirect(action('HomeController@index'))->with('status','新しいスレッドを登録しました。');
    }
}
