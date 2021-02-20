<?php

namespace libDocker\containers;

class PortMapping
{
    /** @var int $portOnHost */
    private $portOnHost;

    /** @var int $portOnDocker */
    private $portOnDocker;

    /** @var string $protocol */
	private $protocol;

	public function __construct(int $portOnHost, int $portOnDocker, string $protocol)
    {
        $this->portOnHost = $portOnHost;

        $this->portOnDocker = $portOnDocker;

        $this->protocol = $protocol;
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
        return "-p {$this->portOnHost}:{$this->portOnDocker}/{$this->protocol}";
    }
}
