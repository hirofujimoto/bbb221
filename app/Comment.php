<?php

namespace App;

use App\Reading;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Comment extends Model
{
    protected $table = "comments";

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function histories()
    {
        return $this->hasMany(History::class);
    }

    public function neighbours()
    {
        $comments = Comment::where('article_id',$this->article_id)
            ->where('root_id', 0)->orderBy('created_at','asc')->get();
        $tree = static::makeTree($comments, []);
        $previous = $next = 0;
        for($i = 0; $i < count($tree); $i++){
            if($tree[$i]['id'] == $this->id ){
                if($i > 0){
                    $previous = $tree[$i-1]['id'];
                }
                if($i < count($tree) -1 ){
                    $next = $tree[$i+1]['id'];
                }
                break;
            }
        }
        return ['previous'=>$previous, 'next'=>$next];
    }

    /*
    public function previous()
    {
        $comments = Comment::where('article_id',$this->article_id)
            ->where('root_id', 0)->orderBy('created_at','asc')->get();
        $tree = static::makeTree($comments, [], 1);
        $prev = 0;
        foreach($tree as $b){
            if($b['id'] == $this->id){
                return $prev;
            }
            $prev = $b['id'];
        }
        return 0;
    }

    public function next()
    {
        $comments = Comment::where('article_id',$this->article_id)
            ->where('root_id', 0)->orderBy('created_at','asc')->get();
        $tree = static::makeTree($comments, [], 1);
        $find = false;
        foreach($tree as $b){
            if($find){
                return $b['id'];
            }elseif($b['id'] == $this->id){
                $find = true;
            }
        }
        return 0;
    }
    */

    public static function makeTree($comments, $tree = array(), $depth = 0)
    {
        $depth++;
        foreach($comments as $com){
            $comset = array(
                'id' => $com->id,
                'user' => $com->user->name,
                'head' => sprintf("%s...", mb_substr($com->message,0,25)),
                'date' => $com->created_at,
                'depth' => $depth
            );
            array_push($tree,$comset);
            $branches = Comment::where('root_id', $com->id)->orderBy('created_at', 'asc')->get();
            if(count($branches)){
                $tree = static::makeTree($branches,$tree,$depth);
            }  
        }
        return $tree;        
    }

}
