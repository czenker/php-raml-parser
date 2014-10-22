<?php
namespace Xopn\PhpRamlParser\Parser;

use Xopn\PhpRamlParser\Domain\Method;

class MethodParser extends AbstractParser {

	protected $className = '\\Xopn\\PhpRamlParser\\Domain\\Method';

	/**
	 * @var NamedParameterParser
	 */
	protected $namedParameterParser;

	/**
	 * @var UriParameterParser
	 */
	protected $uriParameterParser;

	/**
	 * @var ResponseParser
	 */
	protected $responseParser;

	public function __construct() {
		// @TODO: DI
		$this->namedParameterParser = new NamedParameterParser();
		$this->uriParameterParser = new UriParameterParser();
		$this->responseParser = new ResponseParser();
	}

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
	public function setHeaders(Method $method, $data) {
		foreach($data as $key => $dat) {
			$parameter = $this->namedParameterParser->parse($dat, $key);
			$method->addHeader($parameter, $key);
		}
	}

	/**
	 * @param Method $method
	 * @param $data
	 */
	protected function setQueryParameters(Method $method, $data) {
		foreach($data as $name => $parameter) {
			$parameter = $this->namedParameterParser->parse($parameter, $name);
			$method->addQueryParameter($parameter, $name);
		}
	}

	/**
	 * @param Method $method
	 * @param $data
	 */
	protected function setResponses(Method $method, $data) {
		foreach($data as $statusCode => $conf) {
			$response = $this->responseParser->parse($conf, $statusCode);
			$method->addResponse($response, $statusCode);
		}
	}

	/**
	 * @param Method $definition
	 * @param $data
	 */
	protected function setBaseUriParameters(Method $definition, $data) {
		foreach($data as $name => $parameter) {
			$parameter = $this->uriParameterParser->parse($parameter, $name);
			$definition->addBaseUriParameter($parameter, $name);
		}
	}
}