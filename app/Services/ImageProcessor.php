<?php

namespace App\Services;

use App\Events\NewPhotoAdded;
use App\Events\NewVideoAdded;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;

use App\Photo;
use App\Services\MediaProcessor;

class ImageProcessor extends MediaProcessor {

    const IMAGE_PATH = 'photos';
    const IMAGE_EXT = 'jpg';

    public function __construct() {
        $this->imageManager = new ImageManager();
        $this->image = null;
        $this->ext = self::IMAGE_EXT;
    }

    public function process($file)
    {
        Log::debug('ImageProcessor process ' . $file);
        $this->image = $this->imageManager->make(storage_path("app/$file"));
        $exif =  json_encode($this->image->exif());
        Log::debug($exif);
        $processed = $this->genFileName();
        Log::debug('saving: '.$processed);
        $this->image->orientate()->save(storage_path("app/$processed"), env('JPEG_QUALITY', 100));
        Storage::disk('s3')->writeStream(self::IMAGE_PATH.'/'.$processed, Storage::disk('local')->readStream($processed));
        $photo = Photo::create(
          ['path' => 'photos/'.$processed, 'exif' => $exif]
        );
        return $photo;
    }



}
