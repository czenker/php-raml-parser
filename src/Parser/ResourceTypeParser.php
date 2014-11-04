<?php
namespace Xopn\PhpRamlParser\Parser;

use Xopn\PhpRamlParser\Domain\ResourceType;

class ResourceTypeParser extends AbstractResourceParser {

	protected $className = '\\Xopn\\PhpRamlParser\\Domain\\ResourceType';

	/**
	 * @param array $data
	 * @param string $objectKey
	 * @return ResourceType
	 */
	public function parse($data, $objectKey = NULL) {
		$resource = parent::parse($data, $objectKey);
		return $resource;
	}
}