<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    public $timestamps = false;

    protected $table = "histories";
    
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

}
