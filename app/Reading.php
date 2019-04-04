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
}
