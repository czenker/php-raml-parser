<?php
namespace Xopn\PhpRamlParser\Parser;

use Xopn\PhpRamlParser\Domain\UriParameter;

class UriParameterParser extends NamedParameterParser {

	protected $className = '\\Xopn\\PhpRamlParser\\Domain\\UriParameter';

	/**
	 * @param array $data
	 * @param string $objectKey
	 * @return UriParameter
	 */
	public function parse($data, $objectKey = NULL) {
		return parent::parse($data, $objectKey);
	}
}