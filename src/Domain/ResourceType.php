<?php
namespace Xopn\PhpRamlParser\Domain;

class ResourceType extends AbstractResource {

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * The usage property of a resource type or trait is used to describe how the resource type or trait should be used.
	 *
	 * @var string
	 */
	protected $usage;

	/**
	 * @param string $name
	 */
	public function __construct($name) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getUsage() {
		return $this->usage;
	}

	/**
	 * @param string $usage
	 */
	public function setUsage($usage) {
		$this->usage = $usage;
	}
}