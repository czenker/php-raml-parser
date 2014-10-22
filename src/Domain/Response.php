<?php
namespace Xopn\PhpRamlParser\Domain;

/**
 * Resource methods MAY have one or more responses.
 */
class Response {

	/**
	 * @var integer
	 */
	protected $statusCode;

	/**
	 * @todo
	 *
	 * @var array<Body>
	 */
	protected $body;

	/**
	 * description property that further clarifies why the response was emitted
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
	 * @param string $propertyKeyName
	 */
	public function __construct($propertyKeyName) {
		$statusCode = intval($propertyKeyName);
		if($statusCode < 100 || $statusCode >= 600) {
			throw new \InvalidArgumentException(sprintf('%s does not look like a valid status code', $propertyKeyName));
		}
		$this->statusCode = $statusCode;
	}

	/**
	 * @return int
	 */
	public function getStatusCode() {
		return $this->statusCode;
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
	public function getHeaders() {
		return $this->headers;
	}

	/**
	 * @param NamedParameter $header
	 * @param string $key
	 */
	public function addHeader(NamedParameter $header, $key) {
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


}