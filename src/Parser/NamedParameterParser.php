<?php
namespace Xopn\PhpRamlParser\Parser;

use Xopn\PhpRamlParser\Domain\NamedParameter;

class NamedParameterParser extends AbstractParser {

	protected $className = '\\Xopn\\PhpRamlParser\\Domain\\NamedParameter';

	/**
	 * @param array $data
	 * @param string $objectKey
	 * @return NamedParameter
	 */
	public function parse($data, $objectKey = NULL) {
		return parent::parse($data, $objectKey);
	}
}