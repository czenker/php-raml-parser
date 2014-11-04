<?php
namespace Xopn\PhpRamlParser\Parser;

use Xopn\PhpRamlParser\Domain\MethodTrait;

class MethodTraitParser extends AbstractMethodParser {

	protected $className = '\\Xopn\\PhpRamlParser\\Domain\\MethodTrait';

	/**
	 * @param array $data
	 * @param string $objectKey
	 * @return MethodTrait
	 */
	public function parse($data, $objectKey = NULL) {
		return parent::parse($data, $objectKey);
	}
}