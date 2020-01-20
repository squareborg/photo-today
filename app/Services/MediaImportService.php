<?php

namespace App\Services;

use App\Events\NewPhotoAdded;
use App\Events\NewVideoAdded;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Photo;
use App\Video;
use Exception;
use PhpParser\Node\Stmt\TryCatch;

class MediaImportService {

    protected $imageProcessor;
    protected $videoProcessor;
    protected $processedImages;

    public function __construct() {
      $this->imageProcessor = new ImageProcessor();
      $this->videoProcessor = new VideoProcessor();
      $this->processedImages = collect();
      $this->processedVideos = collect();
    }

    public function import()
    {
        Log::debug('Import started');
        $files = collect(Storage::disk('dropbox')->files());
        $files->each(function($file){
            Log::debug("Processing $file");
            if (strtolower(pathinfo($file)['extension']) === 'jpg'){
                if($this->copyFileToLocal($file)){
                    $this->processedImages->add($this->imageProcessor->process($file));
                    Storage::disk('dropbox')->delete($file);
                } else {
                  throw new Exception("Error Processing Request", 1);
                }
              }
            if (strtolower(pathinfo($file)['extension']) === 'mov'){
                if($this->copyFileToLocal($file)){
                    $this->processedVideos->add($this->videoProcessor->process($file));
                    Storage::disk('dropbox')->delete($file);
                } else {
                    throw new Exception("Error Processing Request", 1);
                }
            }
        });
        if ($this->processedImages->count()) {
            $latest = Photo::latest()->first();
            Storage::disk('s3')->delete('photos/latest.jpg');
            Storage::disk('s3')->copy($latest->path, 'photos/latest.jpg');
            event(new NewPhotoAdded());
        }
        if ($this->processedVideos->count()) {
          $latest = Video::latest()->first();
          Storage::disk('s3')->delete('videos/latest.mov');
          Storage::disk('s3')->copy($latest->path, 'videos/latest.mov');
          event(new NewVideoAdded());
      }
      return [
        'videos' => $this->processedVideos,
        'photos' => $this->processedImages
      ];
    }

    public function copyFileToLocal($file)
    {
        Log::debug('Copying file');
        try {
          if(Storage::disk('local')->exists($file)) {
            Storage::disk('local')->delete($file);
          }
          Storage::disk('local')->writeStream($file, Storage::disk('dropbox')->readStream($file));
          Log::debug('Copy success');
          return true;
        } catch (\Exception $e) {
          Log::error('Error copying file to local:'.$e->getMessage());
          return false;
        }
    }
}
