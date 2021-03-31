<?php

namespace libDocker\containers;

use libDocker\ComposerDecoy;
use libDocker\containers\Exceptions\CouldNotStartDockerContainer;
use pocketmine\utils\Utils;
use Spatie\Macroable\Macroable;
use Symfony\Component\Process\Process;

ComposerDecoy::load();

class DockerContainer{
	use Macroable;

	public $image = '';

	public $name = '';

	public $daemonize = true;

	/** @var PortMapping[] */
	public $portMappings = [];

	/** @var EnvironmentMapping[] */
	public $environmentMappings = [];

	/** @var VolumeMapping[] */
	public $volumeMappings = [];

	/** @var LabelMapping[] */
	public $labelMappings = [];

	public $cleanUpAfterExit = true;

	public $stopOnDestruct = false;

	public function __construct(string $image, string $name = ''){
		$this->image = $image;

		$this->name = $name;
	}

	public static function create(...$args) : self{
		return new static(...$args);
	}

	public function image(string $image) : self{
		$this->image = $image;

		return $this;
	}

	public function name(string $name) : self{
		$this->name = $name;

		return $this;
	}

	public function daemonize(bool $daemonize = true) : self{
		$this->daemonize = $daemonize;

		return $this;
	}

	public function doNotDaemonize() : self{
		$this->daemonize = false;

		return $this;
	}

	public function cleanUpAfterExit(bool $cleanUpAfterExit) : self{
		$this->cleanUpAfterExit = $cleanUpAfterExit;

		return $this;
	}

	public function doNotCleanUpAfterExit() : self{
		$this->cleanUpAfterExit = false;

		return $this;
	}

	public function mapPort(int $portOnHost, int $portOnDocker, string $protocol) : self{
		$this->portMappings[] = new PortMapping($portOnHost, $portOnDocker, $protocol);

		return $this;
	}

	public function setEnvironmentVariable(string $envName, string $envValue) : self{
		$this->environmentMappings[] = new EnvironmentMapping($envName, $envValue);

		return $this;
	}

	public function setVolume(string $pathOnHost, string $pathOnDocker) : self{
		$this->volumeMappings[] = new VolumeMapping($pathOnHost, $pathOnDocker);

		return $this;
	}

	public function setLabel(string $labelName, string $labelValue) : self{
		$this->labelMappings[] = new LabelMapping($labelName, $labelValue);

		return $this;
	}

	public function stopOnDestruct(bool $stopOnDestruct = true) : self{
		$this->stopOnDestruct = $stopOnDestruct;

		return $this;
	}

	public function getStartCommand() : string{
		$command = "docker run {$this->getExtraOptions()} {$this->image}";
		if(Utils::getOS(true) === "win"){
			$command = "powershell -command \"{$command}\"";
		}
		return $command;
	}

	public function start() : DockerContainerInstance{
		$command = $this->getStartCommand();

		$process = Process::fromShellCommandline($command);

		$process->run();

		if(!$process->isSuccessful()){
			throw CouldNotStartDockerContainer::processFailed($this, $process);
		}

		$dockerIdentifier = $process->getOutput();

		return new DockerContainerInstance(
			$this,
			$dockerIdentifier,
			$this->name,
		);
	}

	protected function getExtraOptions() : string{
		$extraOptions = [];

		if(count($this->portMappings)){
			$extraOptions[] = implode(' ', $this->portMappings);
		}

		if(count($this->environmentMappings)){
			$extraOptions[] = implode(' ', $this->environmentMappings);
		}

		if(count($this->volumeMappings)){
			$extraOptions[] = implode(' ', $this->volumeMappings);
		}

		if(count($this->labelMappings)){
			$extraOptions[] = implode(' ', $this->labelMappings);
		}

		if($this->name !== ''){
			$extraOptions[] = "--name \"{$this->name}\"";
		}

		if($this->daemonize){
			$extraOptions[] = '-itd';
			//$extraOptions[] = '-t';
		}

		if($this->cleanUpAfterExit){
			$extraOptions[] = '--rm';
		}

		return implode(' ', $extraOptions);
	}
}
