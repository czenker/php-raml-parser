<?php
namespace Xopn\PhpRamlParser\Parser;

use Xopn\PhpRamlParser\Domain\Method;

class MethodParser extends AbstractMethodParser {

	protected $className = '\\Xopn\\PhpRamlParser\\Domain\\Method';

	/**
	 * @param array $data
	 * @param string $objectKey
	 * @return Method
	 */
	public function parse($data, $objectKey = NULL) {
		return parent::parse($data, $objectKey);
	}

	/**
	 * @param Method $method
	 * @param $data
	 */
	protected function setIs(Method $method, $data) {
		foreach($data as $key => $value) {
			if(is_array($value)) {
				$method->addTrait($key, $value);
			} else {
				$method->addTrait($value);
			}
		}
	}
}