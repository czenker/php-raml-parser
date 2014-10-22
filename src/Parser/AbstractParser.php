<?php
namespace Xopn\PhpRamlParser\Parser;

abstract class AbstractParser {

	/**
	 * @var string
	 */
	protected $className;

	/**
	 * @param array $data
	 * @param string $objectKey
	 * @return object
	 */
	public function parse($data, $objectKey = NULL) {
		$object = $this->instanciateObject($objectKey, $data);
		$this->setKey($object, $objectKey);

		foreach($data as $key => $value) {
			$setterName = 'set' . $key;
			if(method_exists($this, $setterName)) {
				$this->$setterName($object, $value);
			} elseif(method_exists($object, $setterName)) {
				$object->$setterName($value);
			} else {
//				throw new \InvalidArgumentException(sprintf(
//					'Could not set property %s on %s. Method %s does not exist on %s or %s.',
//					$key,
//					get_class($object),
//					$setterName,
//					get_class($this),
//					get_class($object)
//				));
			}
		}

		return $object;
	}

	/**
	 * set the key of the configuration on the object
	 *
	 * @param $object
	 * @param $key
	 */
	protected function setKey($object, $key) {
		// no-op
	}

	/**
	 * @param string $objectKey
	 * @param array  $data
	 * @return mixed
	 */
	protected function instanciateObject($objectKey, $data) {
		if (empty($this->className)) {
			throw new \RuntimeException(sprintf(
				'No className configured for %s',
				get_class($this)
			));
		}

		$object = new $this->className($objectKey);
		return $object;
	}

} 