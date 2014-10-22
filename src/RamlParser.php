<?php
namespace Xopn\PhpRamlParser;

use Symfony\Component\Yaml\Yaml;
use Xopn\PhpRamlParser\Parser\DefinitionParser;

class RamlParser {

	/**
	 * @param $location
	 * @param null $baseUrl
	 * @return \Xopn\PhpRamlParser\Domain\Definition
	 */
	public function parse($location, $baseUrl = NULL) {
		return $this->parseFile($location, $baseUrl);
	}

	/**
	 * @param string $path
	 * @param null|string $baseUrl
	 * @return \Xopn\PhpRamlParser\Domain\Definition
	 */
	public function parseFile($path, $baseUrl = NULL) {
		// @TODO: That's only prototype code
		$structure = Yaml::parse(file_get_contents($path));
		$definitionParser = new DefinitionParser();
		return $definitionParser->parse($structure);
	}
} 