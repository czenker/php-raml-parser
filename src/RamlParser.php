<?php
namespace Xopn\PhpRamlParser;

use Symfony\Component\Yaml\Yaml;
use Xopn\PhpRamlParser\Parser\DefinitionParser;

class RamlParser {

	/**
	 * @var string
	 */
	protected $basePath;

	/**
	 * @param $location
	 * @return \Xopn\PhpRamlParser\Domain\Definition
	 */
	public function parse($location) {
		$this->basePath = dirname($location) . '/';
		$structure = $this->parseRaml($location);

		$definitionParser = new DefinitionParser();
		return $definitionParser->parse($structure);
	}

	/**
	 * @param string $path
	 * @return array|string
	 */
	protected function parseFile($path) {
		$ext = pathinfo($path, PATHINFO_EXTENSION);

		if($ext === 'raml') {
			return $this->parseRaml($path);
		} elseif($ext === 'yaml' || $ext === 'yml') {
			return $this->parseYaml($path);
		} else {
			return $this->parseText($path);
		}
	}

	/**
	 * @param string $path
	 * @return array
	 */
	protected function parseRaml($path) {
		$structure = $this->parseYaml($path);

		// replace '!include'
		array_walk_recursive($structure, function(&$value) {
			if(strncmp('!include ', $value, 9) === 0) {
				$value = $this->parseFile($this->basePath . trim(substr($value, 9)));
			}
		});

		return $structure;
	}

	/**
	 * @param string $path
	 * @return array
	 */
	protected function parseYaml($path) {
		$content = $this->parseText($path);
		return Yaml::parse($content);
	}

	/**
	 * @param string $path
	 * @return string
	 */
	public function parseText($path) {
		$content = file_get_contents($path);
		if($content === NULL) {
			throw new \InvalidArgumentException(sprintf(
				'Content of file at location %s could not be loaded.',
				$path
			));
		}

		return $content;
	}
}