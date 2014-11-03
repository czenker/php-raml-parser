<?php
namespace Xopn\PhpRamlParser\Domain;

/**
 * methods are operations that are performed on a resource.
 *
 * A method MUST be one of the HTTP methods defined in the HTTP version 1.1 specification RFC2616
 * and its extension, RFC5789.
 *
 * @see https://www.ietf.org/rfc/rfc2616.txt
 * @see https://www.ietf.org/rfc/rfc5789.txt
 */
class Method extends AbstractDomain {

	/**
	 * one of the HTTP methods defined in the HTTP version 1.1 specification RFC2616 and its extension, RFC5789
	 *
	 * @var string
	 */
	protected $method;

	/**
	 * The displayName attribute provides a friendly name to the resource
	 *
	 * @var string
	 */
	protected $displayName;

	/**
	 * a description attribute briefly describes what the method does to the resource
	 *
	 * @var string
	 */
	protected $description;

	/**
	 * specify the non-standard HTTP headers
	 *
	 * @var array<NamedParameter>
	 */
	protected $headers = array();

	/**
	 * A RESTful API can be reached HTTP, HTTPS, or both.
	 *
	 * @var array<string>|NULL
	 */
	protected $protocols;

	/**
	 * If the resource or its method supports a query string, the query string MUST be defined
	 *
	 * @var array<NamedParameter>
	 */
	protected $queryParameters = array();

	/**
	 * A resource or a method can override a base URI template's values.
	 *
	 * @var array<UriParameter>
	 */
	protected $baseUriParameters = [];

	/**
	 * @todo
	 *
	 * @var array<Body>
	 */
	protected $body = [];

	/**
	 * @var array<Response>
	 */
	protected $responses = [];

	/**
	 * key is the name of the trait, value its parameters
	 *
	 * @var array<array>
	 */
	protected $traits = [];

	public function __construct($method) {
		$this->method = $method;
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
	 * @return string
	 */
	public function getMethod() {
		return $this->method;
	}

	/**
	 * @param string $method
	 */
	public function setMethod($method) {
		$this->method = $method;
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
	 * @return array
	 */
	public function getQueryParameters() {
		return $this->queryParameters;
	}

	/**
	 * @param NamedParameter $parameter
	 * @param string $name
	 */
	public function addQueryParameter(NamedParameter $parameter, $name) {
		$parameter->setParent($this);
		$this->queryParameters[$name] = $parameter;
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function hasQueryParameter($key) {
		return array_key_exists($key, $this->queryParameters);
	}

	/**
	 * @param $key
	 * @return NamedParameter
	 */
	public function getQueryParameter($key) {
		if(!$this->hasQueryParameter($key)) {
			throw new \InvalidArgumentException(sprintf('QueryParameter with key %s does not exist', $key));
		}
		return $this->queryParameters[$key];
	}

	/**
	 * @return array
	 */
	public function getHeaders() {
		return $this->headers;
	}

	/**
	 * @param NamedParameter $header
	 * @param string $key
	 */
	public function addHeader(NamedParameter $header, $key) {
		$header->setParent($this);
		$key = strtolower($key);
		$this->headers[$key] = $header;
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function hasHeader($key) {
		$key = strtolower($key);
		return array_key_exists($key, $this->headers);
	}

	/**
	 * @param $key
	 * @return mixed
	 */
	public function getHeader($key) {
		$key = strtolower($key);
		if(!$this->hasHeader($key)) {
			throw new \InvalidArgumentException(sprintf('Header with key %s does not exist', $key));
		}
		return $this->headers[$key];
	}

	/**
	 * @return array
	 */
	public function getResponses() {
		return $this->responses;
	}

	/**
	 * @param Response $response
	 * @param string $statusCode
	 */
	public function addResponse(Response $response, $statusCode) {
		$response->setParent($this);
		$this->responses[$statusCode] = $response;
	}

	/**
	 * @param string $statusCode
	 * @return bool
	 */
	public function hasResponse($statusCode) {
		return array_key_exists($statusCode, $this->responses);
	}

	/**
	 * @param $statusCode
	 * @return Response
	 */
	public function getResponse($statusCode) {
		if(!$this->hasResponse($statusCode)) {
			throw new \InvalidArgumentException(sprintf('Response with key %s does not exist', $statusCode));
		}
		return $this->responses[$statusCode];
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