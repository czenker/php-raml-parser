<?php
namespace Xopn\PhpRamlParser\Resolver;

use Xopn\PhpRamlParser\Domain\Definition;
use Xopn\PhpRamlParser\Domain\Method;
use Xopn\PhpRamlParser\Domain\Resource;
use Xopn\PhpRamlParser\Domain\ResourceType;
use Xopn\PhpRamlParser\Parser\MethodTraitParser;
use Xopn\PhpRamlParser\Parser\ResourceTypeParser;

class Resolver {

	/**
	 * @var MethodTraitParser
	 */
	protected $methodTraitParser;

	/**
	 * @var ResourceTypeParser
	 */
	protected $resourceTypeParser;

	/**
	 * @var Merger
	 */
	protected $merger;

	public function __construct() {
		// @TODO DI
		$this->methodTraitParser = new MethodTraitParser();
		$this->resourceTypeParser = new ResourceTypeParser();
		$this->merger = new Merger();
	}

	/**
	 * resolve traits and resourceTypes in the given definition
	 *
	 * This will resolve traits and resourceTypes as if they had been defined in the methods and resources
	 * directly. This operation will potentially make it harder to convert the definition back into readable raml
	 * afterwards.
	 *
	 * @param Definition $definition
	 */
	public function applyResourceTypesAndTraits(Definition $definition) {
		// @TODO: optional properties
		$this->applyResourceTypes($definition);
		$this->applyTraits($definition);
	}

	/**
	 * @param Definition $definition
	 */
	protected function applyResourceTypes(Definition $definition) {
		foreach($definition->getAllResources() as $resource) {
			$this->applyResourceType($resource, $definition);
		}
	}

	/**
	 * @param Resource $resource
	 * @param Definition $definition
	 */
	protected function applyResourceType(Resource $resource, Definition $definition) {
		if($resource->getResourceType() === NULL) {
			return;
		}

		if(!$definition->hasResourceType($resource->getResourceType())) {
			throw new \InvalidArgumentException(sprintf(
				'ResourceType with name %s is not in definition',
				$resource->getResourceType()
			));
		}

		$parameters = $resource->getResourceTypeParameters() ?: [];
		$parameters['resourcePath'] = $resource->getResourcePath();
		$parameters['resourcePathName'] = $resource->getResourcePathName();

		$resourceType = $this->instantiateResourceType(
			$resource->getResourceType(),
			$definition->getResourceType($resource->getResourceType()),
			$parameters
		);

		$this->merger->mergeResources($resourceType, $resource);
	}

	/**
	 * @param string $name
	 * @param array $configuration
	 * @param array $parameters
	 * @return ResourceType
	 */
	protected function instantiateResourceType($name, $configuration, $parameters = []) {
		$finalConfiguration = $this->applyParametersToConfiguration($parameters, $configuration);
		return $this->resourceTypeParser->parse($finalConfiguration, $name);
	}

	/**
	 * @param Definition $definition
	 */
	protected function applyTraits(Definition $definition) {
		foreach($definition->getAllResources() as $resource) {
			foreach($resource->getTraits() as $traitName => $traitParameters) {
				$trait = $definition->getTrait($traitName);
				foreach($resource->getMethods() as $methodName => $method) {
					$this->applyTrait($method, $traitName, $trait, $traitParameters);
				}
			}
		}
		foreach($definition->getAllMethods() as $methodName => $method) {
			foreach($method->getTraits() as $traitName => $traitParameters) {
				$trait = $definition->getTrait($traitName);
				$this->applyTrait($method, $traitName, $trait, $traitParameters);
			}
		}
	}

	protected function applyTrait(Method $method, $traitName, $configuration, $parameters = []) {
		$parameters['resourcePath'] = $method->getParent()->getResourcePath();
		$parameters['resourcePathName'] = $method->getParent()->getResourcePathName();
		$parameters['methodName'] = $method->getMethod();
		$methodTrait = $this->instantiateMethodTrait($traitName, $configuration, $parameters);

		$this->merger->mergeMethods($methodTrait, $method);
	}

	/**
	 * @param string $name
	 * @param array $configuration
	 * @param array $parameters
	 * @return \Xopn\PhpRamlParser\Domain\MethodTrait
	 */
	protected function instantiateMethodTrait($name, $configuration, $parameters = []) {
		$finalConfiguration = $this->applyParametersToConfiguration($parameters, $configuration);
		return $this->methodTraitParser->parse($finalConfiguration, $name);
	}

	/**
	 * @param array $parameters
	 * @param array $configuration
	 * @return array
	 */
	protected function applyParametersToConfiguration($parameters, $configuration) {
		$return = [];
		foreach($configuration as $key => $value) {
			$key = $this->replacePlaceholdersInString($key, $parameters);
			if(is_array($value)) {
				$value = $this->applyParametersToConfiguration($parameters, $value);
			} else {
				$value = $this->replacePlaceholdersInString($value, $parameters);
			}
			$return[$key] = $value;
		}
		return $return;
	}

	/**
	 * @param string $string
	 * @param array $parameters
	 * @return string
	 */
	protected function replacePlaceholdersInString($string, $parameters = []) {
		return preg_replace_callback('/<<\s*(\w+)\s*>>/', function($match) use($parameters) {
			$id = $match[1];
			if(!array_key_exists($id, $parameters)) {
				throw new \RuntimeException(sprintf('marker "%s" can not be replaced'));
			}
			return $parameters[$id];
		}, $string);
	}
}