<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Jobs\Videos\CreateVideoThumbnail;

class UploadVideoController extends Controller
{
    public function index(Channel $channel)
    {
        return view('channels.upload', [
            'channel' => $channel
        ]);
    }

    public function store(Channel $channel)
    {

        request()->validate(['video' => 'mimes:mp4,mov,ogg,qt | max:30000']);

        $video = $channel->videos()->create([
            'title' => request()->title,
            'path' => request()->video->store("channels/{$channel->id}", 's3')
        ]);

        $this->dispatch(new CreateVideoThumbnail($video));

        return $video;
    }
}