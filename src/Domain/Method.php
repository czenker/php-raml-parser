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
class Method extends AbstractMethod {

	/**
	 * key is the name of the trait, value its parameters
	 *
	 * @var array<array>
	 */
	protected $traits = [];

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