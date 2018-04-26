<?php

namespace KayStrobach\Liquefy\Domain\Model;


class Action
{
    /**
     * @var \SplFileInfo
     */
    protected $file;

    public function __construct(\SplFileInfo $fileInfo)
    {
        $this->file = $fileInfo;
    }
}