<?php
namespace Xopn\PhpRamlParser\Domain;

class MethodTrait extends AbstractMethod {

	/**
	 * @var string
	 */
	protected $usage;

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