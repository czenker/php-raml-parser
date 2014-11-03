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
	protected $content;

	/**
	 * @return NULL|string
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * @param NULL|string $description
	 */
	public function setContent($description) {
		$this->content = $description;
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