<?php
namespace Xopn\PhpRamlParser\Parser;

use Xopn\PhpRamlParser\Domain\Resource;

class ResourceParser extends AbstractParser {

	protected $className = '\\Xopn\\PhpRamlParser\\Domain\\Resource';

	protected $validMethods = [
		// rfc2616
		'options',
		'get',
		'head',
		'post',
		'put',
		'delete',
		'trace',
		'connect',
		// rfc5789
		'patch',
	];

	/**
	 * @var UriParameterParser
	 */
	protected $uriParameterParser;

	/**
	 * @var MethodParser
	 */
	protected $methodParser;

	public function __construct() {
		// @TODO: DI
		$this->uriParameterParser = new UriParameterParser();
		$this->methodParser = new MethodParser();
	}

	/**
	 * @param array $data
	 * @param string $objectKey
	 * @return Resource
	 */
	public function parse($data, $objectKey = NULL) {
		$resource = parent::parse($data, $objectKey);
		$this->setResources($resource, $data);
		$this->setMethods($resource, $data);
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
	protected function setBaseUriParameters(Resource $resource, $data) {
		foreach($data as $name => $parameter) {
			$parameter = $this->uriParameterParser->parse($parameter, $name);
			$resource->addBaseUriParameter($parameter, $name);
		}
	}

	/**
	 * @param Resource $resource
	 * @param $data
	 */
	protected function setMethods(Resource $resource, $data) {
		foreach($data as $methodName => $config) {
			if(in_array(strtolower($methodName), $this->validMethods)) {
				$method = $this->methodParser->parse($config, $methodName);
				$resource->addMethod($method, $methodName);
			}
		}
	}

}