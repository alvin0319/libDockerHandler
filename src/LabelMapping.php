<?php

namespace Spatie\Docker;

class LabelMapping
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

    public function __toString()
    {
        return "-l {$this->name}={$this->value}";
    }
}
