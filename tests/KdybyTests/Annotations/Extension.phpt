<?php

/**
 * Test: Kdyby\Doctrine\Extension.
 *
 * @testCase Kdyby\Doctrine\ExtensionTest
 * @author Filip Procházka <filip@prochazka.su>
 * @package Kdyby\Doctrine
 */

namespace KdybyTests\Annotations;

use Doctrine;
use Doctrine\Common\Annotations\Reader;
use Kdyby;
use Nette;
use Tester;
use Tester\Assert;

require_once __DIR__ . '/../bootstrap.php';



/**
 * @author Filip Procházka <filip@prochazka.su>
 */
class ExtensionTest extends Tester\TestCase
{

	/**
	 * @param string $configFile
	 * @return \SystemContainer|Nette\DI\Container
	 */
	public function createContainer($configFile)
	{
		$config = new Nette\Configurator();
		$config->setTempDirectory(TEMP_DIR);
		$config->addParameters(['container' => ['class' => 'SystemContainer_' . md5($configFile)]]);
		$config->addConfig(__DIR__ . '/../nette-reset.neon');
		$config->addConfig(__DIR__ . '/config/' . $configFile . '.neon');
		Kdyby\Annotations\DI\AnnotationsExtension::register($config);

		return $config->createContainer();
	}



	public function testFunctionality()
	{
		$container = $this->createContainer('ignored');
		$reader = $container->getByType(Reader::class);
		Assert::true($reader instanceof Reader);
		/** @var \Doctrine\Common\Annotations\Reader $reader */

		require_once __DIR__ . '/files/ignored.php';
		$annotations = $reader->getPropertyAnnotations(new \ReflectionProperty(Dj::class, 'music'));
		Assert::equal([
			new HandsInTheAir([])
		], $annotations);
	}

}

(new ExtensionTest())->run();
