<?php

namespace libDocker\containers;

class LabelMapping{
	/** @var string $name */
	private $name;

	/** @var string $value */
	private $value;

	public function __construct(string $name, string $value){
		$this->name = $name;

		$this->value = $value;
	}

	public function getName(){
		return $this->name;
	}

	public function getValue(){
		return $this->value;
	}

	public function __toString(){
		return "-l {$this->name}={$this->value}";
	}
}
