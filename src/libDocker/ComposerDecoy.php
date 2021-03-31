<?php
declare(strict_types=1);

namespace libDocker;

use Phar;
use pocketmine\Server;
use function define;
use function defined;
use function dirname;
use function is_file;
use function trigger_error;

class ComposerDecoy{
	/**
	 * Dummy function called to invoke the pocketmine class loader
	 */
	public static function load() : void{
	}

	/**
	 * Require the composer autoload file whenever this class is loaded by the pocketmine class loader
	 */
	public static function onClassLoaded() : void{
		if(!defined('libDocker\COMPOSER_AUTOLOADER_PATH')){
			if(Phar::running(true) !== ""){
				define('libDocker\COMPOSER_AUTOLOADER_PATH',
					Phar::running(true) . "/vendor/autoload.php");
			}elseif(is_file($path = dirname(__DIR__, 2) . "/vendor/autoload.php")){
				define('libDocker\COMPOSER_AUTOLOADER_PATH', $path);
			}else{
				Server::getInstance()->getLogger()->debug("Composer autoloader not found.");
				Server::getInstance()->getLogger()
					->debug("Please install/update Composer dependencies or use provided releases.");
				trigger_error("Couldn't find composer autoloader", E_USER_ERROR);

				return;
			}
		}
		require_once(COMPOSER_AUTOLOADER_PATH);
	}
}