<?php
namespace Xopn\PhpRamlParser\Parser;

use Xopn\PhpRamlParser\Domain\UserDocumentation;

class UserDocumentationParser extends AbstractParser {

	protected $className = '\\Xopn\\PhpRamlParser\\Domain\\UserDocumentation';

	/**
	 * @param array $data
	 * @param string $objectKey
	 * @return UserDocumentation
	 */
	public function parse($data, $objectKey = NULL) {
		return parent::parse($data, $objectKey);
	}
}