<?php

namespace App\Jobs\Videos;

use App\Video;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use FFMpeg\Coordinate\Dimension;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Pbmedia\LaravelFFMpeg\FFMpegFacade as FFMpeg;

class ConvertForStreaming implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $video;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Video $video)
    {
        dd($this->video, '', $video);
        $this->video = $video;
    }

    /**
     * Execute the job.
     *
     * @return void
     */

    public function handle()
    {
        $low = (new X264('aac'))->setKiloBitrate(100);
        $mid = (new X264('aac'))->setKiloBitrate(250);
        $high = (new X264('aac'))->setKiloBitrate(500);
        FFMpeg::fromDisk('s3')
            ->open($this->video->path)
            ->exportForHLS()
            ->toDisk('s3')
            ->onProgress(function ($percentage) {
                $this->video->update(['percentage' => round($percentage)]);
            })
            ->addFormat($low)
            ->addFormat($mid)
            ->addFormat($high)
            ->save("videos/{$this->video->id}/{$this->video->id}.m3u8");
    }




    public function failed()
    {
    }
}