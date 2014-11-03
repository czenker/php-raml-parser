<?php
namespace Xopn\PhpRamlParser\Parser;

use Xopn\PhpRamlParser\Domain\Resource;

class ResourceParser extends AbstractResourceParser {

	protected $className = '\\Xopn\\PhpRamlParser\\Domain\\Resource';

	/**
	 * @param array $data
	 * @param string $objectKey
	 * @return Resource
	 */
	public function parse($data, $objectKey = NULL) {
		$resource = parent::parse($data, $objectKey);
		$this->setResources($resource, $data);
		return $resource;
	}

	/**
	 * @param Resource $resource
	 * @param $data
	 */
	protected function setResources(Resource $resource, $data) {
		foreach($data as $path => $config) {
			if($path[0] === '/') {
				$subResource = $this->parse($config, $path);
				$resource->addResource($subResource, $path);
			}
		}
	}

	/**
	 * @param Resource $resource
	 * @param $data
	 */
	protected function setType(Resource $resource, $data) {
		if(is_array($data)) {
			if(count($data) > 1) {
				throw new \InvalidArgumentException('Only one resource type can be set');
			}
			foreach($data as $name => $parameters) {
				$resource->setResourceType($name, $parameters);
			}
		} else {
			$resource->setResourceType($data);
		}
	}

	/**
	 * @param Resource $resource
	 * @param $data
	 */
	protected function setIs(Resource $resource, $data) {
		foreach($data as $key => $value) {
			if(is_array($value)) {
				$resource->addTrait($key, $value);
			} else {
				$resource->addTrait($value);
			}
		}
	}
}