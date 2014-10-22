<?php

namespace Xopn\PhpRamlParserTests;
use Symfony\Component\Yaml\Yaml;

/**
 * Base Class for Testing
 */
abstract class AbstractTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @param $path
	 * @return array
	 */
	protected function getFixture($path) {
		if(!file_exists($path)) {
			throw new \InvalidArgumentException(sprintf('File %s does not exist', $path));
		}
		return Yaml::parse(file_get_contents($path));
	}

}