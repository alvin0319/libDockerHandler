<?php

namespace libDocker;

class PortMapping
{
    /** @var int $portOnHost */
    private $portOnHost;

    /** @var int $portOnDocker */
    private $portOnDocker;

    public function __construct(int $portOnHost, int $portOnDocker)
    {
        $this->portOnHost = $portOnHost;

        $this->portOnDocker = $portOnDocker;
    }

    public function getPortOnHost()
    {
        return $this->portOnHost;
    }

    public function getPortOnDocker()
    {
        return $this->portOnDocker;
    }

    public function __toString()
    {
        return "-p {$this->portOnHost}:{$this->portOnDocker}";
    }
}
