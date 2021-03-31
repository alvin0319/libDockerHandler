<?php

namespace libDocker\containers;

use libDocker\ComposerDecoy;
use Spatie\Macroable\Macroable;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

ComposerDecoy::load();

class DockerContainerInstance{
	use Macroable;

	/** @var DockerContainer $config */
	private $config;

	/** @var string $dockerIdentifier */
	private $dockerIdentifier;

	/** @var string $name */
	private $name;

	public function __construct(
		DockerContainer $config,
		string $dockerIdentifier,
		string $name
	){
		$this->config = $config;

		$this->dockerIdentifier = $dockerIdentifier;

		$this->name = $name;
	}

	public function __destruct(){
		if($this->config->stopOnDestruct){
			$this->stop();
		}
	}

	public function stop() : Process{
		$fullCommand = "docker stop {$this->getShortDockerIdentifier()}";

		$process = Process::fromShellCommandline($fullCommand);

		$process->run();

		return $process;
	}

	public function getName() : string{
		return $this->name;
	}

	public function getConfig() : DockerContainer{
		return $this->config;
	}

	public function getDockerIdentifier() : string{
		return $this->dockerIdentifier;
	}

	public function getShortDockerIdentifier() : string{
		return substr($this->dockerIdentifier, 0, 12);
	}

	/**
	 * @param string|array $command
	 *
	 * @return Process
	 */
	public function execute($command) : Process{
		if(is_array($command)){
			$command = implode(';', $command);
		}

		$fullCommand = "echo \"{$command}\" | docker exec --interactive {$this->getShortDockerIdentifier()} bash -";

		$process = Process::fromShellCommandline($fullCommand);

		$process->run();

		return $process;
	}

	public function addPublicKey(string $pathToPublicKey, string $pathToAuthorizedKeys = '/root/.ssh/authorized_keys') : self{
		$publicKeyContents = trim(file_get_contents($pathToPublicKey));

		$this->execute('echo \'' . $publicKeyContents . '\' >> ' . $pathToAuthorizedKeys);

		$this->execute("chmod 600 {$pathToAuthorizedKeys}");
		$this->execute("chown root:root {$pathToAuthorizedKeys}");

		return $this;
	}

	public function addFiles(string $fileOrDirectoryOnHost, string $pathInContainer) : self{
		$process = Process::fromShellCommandline("docker cp {$fileOrDirectoryOnHost} {$this->getShortDockerIdentifier()}:{$pathInContainer}");
		$process->run();

		if(!$process->isSuccessful()){
			throw new ProcessFailedException($process);
		}

		return $this;
	}
}
