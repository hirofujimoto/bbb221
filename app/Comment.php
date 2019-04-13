<?php

namespace App;

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

    public function root(){
        return $this->belongsTo(Comment::class);
    }

    public function histories()
    {
        return $this->hasMany(History::class);
    }

    public function previous()
    {
        $prev = Comment::where('article_id',$this->article_id)->where('id','<',$this->id)->orderBy('id','desc')->first();
        if($prev == NULL){
            return 0;
        }
        return  $prev->id;
    }

    public function next()
    {
        $next = Comment::where('article_id',$this->article_id)->where('id','>',$this->id)->orderBy('id','asc')->first();
        if($next == NULL){
            return 0;
        }
        return  $next->id;
    }

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
