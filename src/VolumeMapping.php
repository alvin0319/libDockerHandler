<?php

namespace Spatie\Docker;

class VolumeMapping
{
    /** @var string $pathOnHost */
    private $pathOnHost;

    /** @var string $pathOnDocker */
    private $pathOnDocker;

    public function __construct(string $pathOnHost, string $pathOnDocker)
    {
        $this->pathOnHost = $pathOnHost;

        $this->pathOnDocker = $pathOnDocker;
    }

    public function __toString()
    {
        return "-v {$this->pathOnHost}:{$this->pathOnDocker}";
    }
}
