<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class Photo extends Model
{
    use HasUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hash',
        'path',
        'exif',
    ];

    protected $appends = [
        'fullPath',
    ];

    public function getFullPathAttribute()
    {
        return sprintf('%s/%s', storage_path('app'), $this->path);
    }
}
