<?php
namespace Xopn\PhpRamlParser\Domain;

class Resource extends AbstractResource {

	/**
	 * The uri of this resource relative to its parent resource's uri or the baseUri (if it has no parent)
	 *
	 * @var string
	 */
	protected $relativeUri;

	/**
	 * @var array<Resource>
	 */
	protected $resources = [];

	/**
	 * key is the name of the trait, value its parameters
	 *
	 * @var array<array>
	 */
	protected $traits = [];

	/**
	 * @var string
	 */
	protected $resourceType;

	/**
	 * @var array
	 */
	protected $resourceTypeParameters = [];

	/**
	 * @param string $relativeUri
	 */
	public function __construct($relativeUri) {
		$this->relativeUri = $relativeUri;
	}

	/**
	 * @return string
	 */
	public function getRelativeUri() {
		return $this->relativeUri;
	}

	/**
	 * @param string $relativeUri
	 */
	public function setRelativeUri($relativeUri) {
		$this->relativeUri = $relativeUri;
	}

	/**
	 * @return array
	 */
	public function getResources() {
		return $this->resources;
	}

	/**
	 * @param Resource $resource
	 * @param string $path
	 */
	public function addResource(Resource $resource, $path) {
		$this->resources[$path] = $resource;
		$resource->setParent($this);
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function hasResource($key) {
		return array_key_exists($key, $this->resources);
	}

	/**
	 * @param $key
	 * @return mixed
	 */
	public function getResource($key) {
		if(!$this->hasResource($key)) {
			throw new \InvalidArgumentException(sprintf('Resource with key %s does not exist', $key));
		}
		return $this->resources[$key];
	}

	/**
	 * Get the uri relative to the definitions baseUri, no matter how nested the resources are
	 *
	 * @return string
	 */
	public function getFullRelativeUri() {
		if($this->parent instanceof self) {
			return $this->parent->getFullRelativeUri() . $this->getRelativeUri();
		} else {
			return $this->getRelativeUri();
		}
	}

	/**
	 * @return array<Resource>
	 */
	public function getAllResources() {
		$resources = array_values($this->getResources());
		foreach($this->getResources() as $resource) {
			$resources = array_merge($resources, $resource->getAllResources());
		}

		return $resources;
	}

	/**
	 * get all methods of this resource and all of its subresources
	 *
	 * @return array<Method>
	 */
	public function getAllMethods() {
		$methods = array_values($this->getMethods());
		foreach($this->getResources() as $resource) {
			$methods = array_merge($methods, $resource->getAllMethods());
		}

		return $methods;
	}

	/**
	 * @param string $resourceType
	 * @param array $parameters
	 */
	public function setResourceType($resourceType, $parameters = []) {
		$this->resourceType = $resourceType;
		$this->resourceTypeParameters = $parameters;
	}

	/**
	 * @return string
	 */
	public function getResourceType() {
		return $this->resourceType;
	}

	/**
	 * @return array
	 */
	public function getResourceTypeParameters() {
		return $this->resourceTypeParameters;
	}

	/**
	 * @return array
	 */
	public function getTraits() {
		return $this->traits;
	}

	/**
	 * @param string $name
	 * @param array $parameters
	 */
	public function addTrait($name, $parameters = []) {
		$this->traits[$name] = $parameters;
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function hasTrait($name) {
		return array_key_exists($name, $this->traits);
	}

	/**
	 * @param string $name
	 * @return array
	 */
	public function getTraitParameters($name) {
		if(!$this->hasTrait($name)) {
			throw new \InvalidArgumentException(sprintf('Trait with name %s is not set for this resource', $name));
		}
		return $this->traits[$name];
	}
}