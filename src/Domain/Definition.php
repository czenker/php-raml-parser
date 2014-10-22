<?php
namespace Xopn\PhpRamlParser\Domain;

/**
 * The parsed content of the root RAML file
 */
class Definition extends AbstractDomain {

	/**
	 * The title property is a short plain text description of the RESTful API.
	 *
	 * @var string
	 */
	protected $title;

	/**
	 * If the RAML API definition is targeted to a specific API version,
	 * the API definition MUST contain a version property.
	 *
	 * @var string|NULL
	 */
	protected $version;

	/**
	 * A RESTful API's resources are defined relative to the API's base URI.
	 *
	 * @var string|NULL
	 */
	protected $baseUri;

	/**
	 * Any other URI template variables appearing in the baseUri MAY be described explicitly
	 * within a baseUriParameters property at the root of the API definition.
	 *
	 * @var array<UriParameter>|NULL
	 */
	protected $baseUriParameters;

	/**
	 * A RESTful API can be reached HTTP, HTTPS, or both.
	 *
	 * @var array<string>|NULL
	 */
	protected $protocols;

	/**
	 * The media types returned by API responses, and expected from API requests that accept a body,
	 * MAY be defaulted by specifying the mediaType property.
	 *
	 * @var string|NULL
	 */
	protected $mediaType;

	/**
	 * The schemas property specifies collections of schemas that could be used anywhere in the API definition.
	 *
	 * @var array
	 */
	protected $schemas = [];

	/**
	 * The uriParameters property MUST be a map in which each key MUST be the name of the URI
	 * parameter as defined in the baseUri property.
	 *
	 * @var array<UriParameter>
	 */
	protected $uriParameters = [];

	/**
	 * The API definition can include a variety of documents that serve as a user guides and
	 * reference documentation for the API. Such documents can clarify how the API works or provide business context.
	 *
	 * @var array<UserDocumentation>
	 */
	protected $documentation = [];

	/**
	 * @var array<Resource>
	 */
	protected $resources = [];

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

	/**
	 * @return NULL|string
	 */
	public function getVersion() {
		return $this->version;
	}

	/**
	 * @param NULL|string $version
	 */
	public function setVersion($version) {
		$this->version = $version;
	}

	/**
	 * @return NULL|string
	 */
	public function getBaseUri() {
		return $this->baseUri;
	}

	/**
	 * @param NULL|string $baseUri
	 */
	public function setBaseUri($baseUri) {
		$this->baseUri = $baseUri;
	}

	/**
	 * @return array
	 */
	public function getProtocols() {
		return $this->protocols;
	}

	/**
	 * @param array $protocols
	 */
	public function setProtocols($protocols) {
		$this->protocols = $protocols;
	}

	/**
	 * @return NULL|string
	 */
	public function getMediaType() {
		return $this->mediaType;
	}

	/**
	 * @param NULL|string $mediaType
	 */
	public function setMediaType($mediaType) {
		$this->mediaType = $mediaType;
	}

	/**
	 * @return array
	 */
	public function getSchemas() {
		return $this->schemas;
	}

	/**
	 * @param string $schema
	 * @param string $name
	 */
	public function addSchema($schema, $name) {
		$this->schemas[$name] = $schema;
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function hasSchema($key) {
		return array_key_exists($key, $this->schemas);
	}

	/**
	 * @param $key
	 * @return mixed
	 */
	public function getSchema($key) {
		if(!$this->hasSchema($key)) {
			throw new \InvalidArgumentException(sprintf('Schema with key %s does not exist', $key));
		}
		return $this->schemas[$key];
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
	public function getUriParameters() {
		return $this->uriParameters;
	}

	/**
	 * @param UriParameter $parameter
	 * @param string $name
	 */
	public function addUriParameter(UriParameter $parameter, $name) {
		$this->uriParameters[$name] = $parameter;
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function hasUriParameter($key) {
		return array_key_exists($key, $this->uriParameters);
	}

	/**
	 * @param $key
	 * @return UriParameter
	 */
	public function getUriParameter($key) {
		if(!$this->hasUriParameter($key)) {
			throw new \InvalidArgumentException(sprintf('UriParameter with key %s does not exist', $key));
		}
		return $this->uriParameters[$key];
	}

	/**
	 * @return array
	 */
	public function getDocumentation() {
		return $this->documentation;
	}

	/**
	 * @param UserDocumentation $userDocumentation
	 */
	public function addDocumentation(UserDocumentation $userDocumentation) {
		$this->documentation[] = $userDocumentation;
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
	 * @return Resource
	 */
	public function getResource($key) {
		if(!$this->hasResource($key)) {
			throw new \InvalidArgumentException(sprintf('Resource with key %s does not exist', $key));
		}
		return $this->resources[$key];
	}

} 