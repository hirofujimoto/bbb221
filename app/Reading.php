<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reading extends Model
{
    public $timestamps = false;

    protected $table = "readings";

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public static function readArticle($article_id){
        if(static::where('article_id',$article_id)->where('user_id',\Auth::user()->id)->count()){
            return true;
        }
        return false;
    }

    public static function readComment($comment_id){
        if(static::where('comment_id',$comment_id)->where('user_id',\Auth::user()->id)->count()){
            return true;
        }
        return false;
    }

}
