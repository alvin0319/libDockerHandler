<?php

namespace libDocker\containers;

class EnvironmentMapping
{
    /** @var string $name */
    private $name;

    /** @var string $value */
    private $value;

    public function __construct(string $name, string $value)
    {
        $this->name = $name;

        $this->value = $value;
    }

	public function getName()
	{
		return $this->name;
	}

	public function getValue()
	{
		return $this->value;
	}

    public function __toString()
    {
        return "-e {$this->name}={$this->value}";
    }
}
