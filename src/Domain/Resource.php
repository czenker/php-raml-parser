<?php
namespace Xopn\PhpRamlParser\Domain;

class Resource extends AbstractDomain {

	/**
	 * The uri of this resource relative to its parent resource's uri or the baseUri (if it has no parent)
	 *
	 * @var string
	 */
	protected $relativeUri;

	/**
	 * The displayName attribute provides a friendly name to the resource
	 *
	 * It is a friendly name used only for display or documentation purposes.
	 * If displayName is not specified, it defaults to the property's key (the name of the property itself).
	 *
	 * @var string
	 */
	protected $displayName;

	/**
	 * a description property briefly describes the resource
	 *
	 * @var string
	 */
	protected $description;

	/**
	 * A resource MAY contain a uriParameters property specifying the uriParameters in that resource's
	 * relative URI, as described in the Named Parameters section of this specification.
	 *
	 * @var array<UriParameter>
	 */
	protected $uriParameters = [];

	/**
	 * A resource MAY contain a uriParameters property specifying the uriParameters in that resource's
	 * relative URI, as described in the Named Parameters section of this specification.
	 *
	 * @var array<UriParameter>
	 */
	protected $baseUriParameters = [];

	/**
	 * methods are operations that are performed on a resource
	 *
	 * @var array<Method>
	 */
	protected $methods = [];

	/**
	 * @var array<Resource>
	 */
	protected $resources = [];

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
	 * @return string
	 */
	public function getDisplayName() {
		return $this->displayName;
	}

	/**
	 * @param string $displayName
	 */
	public function setDisplayName($displayName) {
		$this->displayName = $displayName;
	}

	/**
	 * @return string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
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
	 * @return array
	 */
	public function getBaseUriParameters() {
		return $this->baseUriParameters;
	}

	/**
	 * @param UriParameter $parameter
	 * @param string $name
	 */
	public function addBaseUriParameter(UriParameter $parameter, $name) {
		$parameter->setParent($this);
		$this->baseUriParameters[$name] = $parameter;
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function hasBaseUriParameter($key) {
		return array_key_exists($key, $this->baseUriParameters);
	}

	/**
	 * @param $key
	 * @return UriParameter
	 */
	public function getBaseUriParameter($key) {
		if(!$this->hasBaseUriParameter($key)) {
			throw new \InvalidArgumentException(sprintf('BaseUriParameter with key %s does not exist', $key));
		}
		return $this->baseUriParameters[$key];
	}

	/**
	 * @return array
	 */
	public function getMethods() {
		return $this->methods;
	}

	/**
	 * @param Method $parameter
	 * @param string $name
	 */
	public function addMethod(Method $parameter, $name) {
		$parameter->setParent($this);
		$this->methods[$name] = $parameter;
	}

	/**
	 * @param string $name
	 * @return bool
	 */
	public function hasMethod($name) {
		return array_key_exists($name, $this->methods);
	}

	/**
	 * @param $name
	 * @return Method
	 */
	public function getMethod($name) {
		if(!$this->hasMethod($name)) {
			throw new \InvalidArgumentException(sprintf('Method with key %s does not exist', $name));
		}
		return $this->methods[$name];
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


} 