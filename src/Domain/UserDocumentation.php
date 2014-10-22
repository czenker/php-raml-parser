<?php
namespace Xopn\PhpRamlParser\Domain;

class UserDocumentation extends AbstractDomain {

	/**
	 * @var string|NULL
	 */
	protected $title;

	/**
	 * @var string|NULL
	 */
	protected $description;

	/**
	 * @return NULL|string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param NULL|string $description
	 */
	public function setDescription($description) {
		$this->description = $description;
	}

	/**
	 * @return NULL|string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @param NULL|string $title
	 */
	public function setTitle($title) {
		$this->title = $title;
	}

} 