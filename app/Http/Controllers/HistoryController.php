<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\History;

class HistoryController extends Controller
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

    public function article($id, $depth = 1)
    {
        $count = History::where('article_id', $id)->where('comment_id', 0)->count();
        $history = History::where('article_id', $id)->where('comment_id', 0)
            ->orderBy('changed_at','desc')->offset($depth-1)->first();
        
        return View('history/article')
            ->with('history', $history)->with('count',$count)->with('depth',$depth);
    }

    public function comment($id, $depth = 1)
    {
        $count = History::where('comment_id', $id)->count();
        $history = History::where('comment_id', $id)
            ->orderBy('changed_at','desc')->offset($depth-1)->first();
        
        return View('history/comment')
            ->with('history', $history)->with('count',$count)->with('depth',$depth);
    }
}
