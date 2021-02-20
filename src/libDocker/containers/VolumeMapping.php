<?php

namespace libDocker\containers;

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

    public function getPathOnHost()
    {
        return $this->pathOnHost;
    }

    public function getPathOnDocker()
    {
        return $this->pathOnDocker;
    }

    public function __toString()
    {
        return "-v \"{$this->pathOnHost}:{$this->pathOnDocker}\"";
    }
}
