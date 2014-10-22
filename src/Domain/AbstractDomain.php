<?php
namespace Xopn\PhpRamlParser\Domain;

abstract class AbstractDomain {

	/**
	 * @var AbstractDomain|NULL
	 */
	protected $parent;

	/**
	 * @return AbstractDomain|NULL
	 */
	public function getParent() {
		return $this->parent;
	}

	/**
	 * @param AbstractDomain|NULL $parent
	 */
	public function setParent($parent = NULL) {
		$this->parent = $parent;
	}
} 