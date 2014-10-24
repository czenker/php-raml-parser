<?php
namespace Xopn\PhpRamlParser\Parser;

use Xopn\PhpRamlParser\Domain\Definition;

class DefinitionParser extends AbstractParser {

	protected $className = '\\Xopn\\PhpRamlParser\\Domain\\Definition';

	/**
	 * @var NamedParameterParser
	 */
	protected $namedParameterParser;

	/**
	 * @var UriParameterParser
	 */
	protected $uriParameterParser;

	/**
	 * @var UserDocumentationParser
	 */
	protected $userDocumentationParser;

	/**
	 * @var ResourceParser
	 */
	protected $resourceParser;

	public function __construct() {
		// @TODO: DI
		$this->namedParameterParser = new NamedParameterParser();
		$this->uriParameterParser = new UriParameterParser();
		$this->userDocumentationParser = new UserDocumentationParser();
		$this->resourceParser = new ResourceParser();
	}

	/**
	 * @param array $data
	 * @param string $objectKey
	 * @return Definition
	 */
	public function parse($data, $objectKey = NULL) {
		$definition = parent::parse($data, $objectKey);
		$this->setResources($definition, $data);
		return $definition;
	}

	/**
	 * @param Definition $definition
	 * @param $data
	 */
	protected function setSchemas(Definition $definition, $data) {
		foreach($data as $dat) {
			foreach($dat as $name => $schema) {
				$definition->addSchema($schema, $name);
			}
		}
	}

	/**
	 * @param Definition $definition
	 * @param $data
	 */
	protected function setUriParameters(Definition $definition, $data) {
		foreach($data as $name => $parameter) {
			$parameter = $this->uriParameterParser->parse($parameter, $name);
			$definition->addUriParameter($parameter, $name);
		}
	}

	/**
	 * @param Definition $definition
	 * @param $data
	 */
	protected function setBaseUriParameters(Definition $definition, $data) {
		foreach($data as $name => $parameter) {
			$parameter = $this->uriParameterParser->parse($parameter, $name);
			$definition->addBaseUriParameter($parameter, $name);
		}
	}

	/**
	 * @param Definition $definition
	 * @param $data
	 */
	protected function setDocumentation(Definition $definition, $data) {
		foreach($data as $userDoc) {
			$documentation = $this->userDocumentationParser->parse($userDoc);
			$definition->addDocumentation($documentation);
		}
	}

	/**
	 * @param Definition $definition
	 * @param $data
	 */
	protected function setResources(Definition $definition, $data) {
		foreach($data as $path => $config) {
			if($path[0] === '/') {
				$resource = $this->resourceParser->parse($config, $path);
				$definition->addResource($resource, $path);
			}
		}
	}


}