<?php

namespace App\Services;

use Ramsey\Uuid\Uuid;

abstract class MediaProcessor
{

    protected $ext;

    public function __constuct($ext)
    {
        $this->ext = $ext;
    }

    abstract public function process($file);

    public function genFileName() {
        $uuid = Uuid::uuid4();
        return $uuid.'.'.$this->ext;
    }
}
