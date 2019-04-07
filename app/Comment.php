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

    public function histories()
    {
        return $this->hasMany(Hostory::class);
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
}
