<?php

namespace App\Console\Commands;

use App\Events\NewPhotoAdded;
use App\Events\NewVideoAdded;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Intervention\Image\ImageManager;
use App\Photo;
use App\Video;


class photomanage extends Command
{
    protected $imageManager;
    const IMAGE_EXT = 'jpg';


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'photos:manage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ImageManager $imageManager)
    {
        parent::__construct();
        $this->imageManager = $imageManager;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::debug('pm start');

        $newPhoto = false;
        $newVideo = false;
        $files = Storage::disk('dropbox')->files();
        foreach ($files as $key => $value) {
            $pathinfo = pathinfo($value);
            if (strtolower($pathinfo['extension']) === 'jpg'){
                Log::debug($value);
                $newPhoto = true;
                $photo = Photo::create([
                'path' => '',
                ]);
                $photo->path = sprintf('%s.%s', $photo->uuid, self::IMAGE_EXT);
                $photo->save();
                Log::debug('Saving local');
                Storage::disk('local')->delete($value);
                Log::debug(Storage::disk('local')->writeStream($value, Storage::disk('dropbox')->readStream($value)));
                $image = $this->imageManager->make(storage_path("app/$value"));
                Log::debug($image->exif());
                try {
                  Storage::disk('local')->delete($value);
                } catch (\Exception $e) {

                }
                $or = $image->exif();
                $image->orientate();
                $image->save($photo->fullPath, env('JPEG_QUALITY', 100));
                Storage::disk('s3')->writeStream($photo->path, Storage::disk('local')->readStream($photo->path));
                Storage::disk('dropbox')->delete($value);
            }

            if (strtolower($pathinfo['extension']) === 'mov'){
                $newVideo = true;
                $video = Video::create([
                'path' => '',
                ]);
                $video->path = sprintf('%s.%s', $video->uuid, 'mov');
                $video->save();
                Storage::disk('s3')->writeStream($video->path, Storage::disk('dropbox')->readStream($value));
                Storage::disk('dropbox')->delete($value);
            }

        }
        if ($newPhoto) {
            $latest = Photo::latest()->first();
            Storage::disk('s3')->delete('photos/latest.jpg');
            Storage::disk('s3')->copy($latest->path, 'photos/latest.jpg');
            event(new NewPhotoAdded());
        }
        if ($newVideo) {
            $latest = Video::latest()->first();
            Storage::disk('s3')->delete('videos/latest.mov');
            Storage::disk('s3')->copy($latest->path, 'videos/latest.mov');
            event(new NewVideoAdded());
        }

    }
}
