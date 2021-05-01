<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class Video extends Model
{
    use HasUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'path',
    ];

    protected $appends = [
        'fullPath',
    ];

    public function getFullPathAttribute()
    {
        return sprintf('%s/%s', storage_path('app'), $this->path);
    }
}
