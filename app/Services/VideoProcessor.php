<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;
use App\Video;

class VideoProcessor extends MediaProcessor {

    const VIDEO_PATH = 'videos';
    const VIDEO_EXT = 'mov';

    public function __construct() {
        $this->video = null;
        $this->image = null;
        $this->ext = self::VIDEO_EXT;
    }

    public function process($file)
    {
        Log::debug('videoProcessor process ' . $file);
        $processed = $this->genFileName();
        Log::debug('saving: '.$processed);
        Storage::disk('s3')->writeStream(self::VIDEO_PATH.'/'.$processed, Storage::disk('local')->readStream($file));
        $video = Video::create(
          ['path' => 'videos/'.$processed]
        );
        return $video;
    }
}
