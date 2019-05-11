<?php

namespace App;

use App\User;
use App\Comment;
use App\Reading;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{

    protected $table = "articles";

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function histories()
    {
        return $this->hasMany(History::class)->where('comment_id',0);
    }

    public function readings()
    {
        $all_message_count = count($this->comments) + 1;
        $all_reading_count = Reading::where('article_id',$this->id)->where('user_id',\Auth::user()->id)->count();
        return  sprintf("%d/%d",$all_message_count-$all_reading_count, $all_message_count);
    }

}
