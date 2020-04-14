<?php

namespace App\Http\Controllers;

use App\Video;

class VoteController extends Controller
{
    public function vote(Video $video, $type)
    {
        return auth()->user()->toggleVote($video, $type);
    }
}
