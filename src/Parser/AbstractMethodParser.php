<?php
namespace Xopn\PhpRamlParser\Parser;

use Xopn\PhpRamlParser\Domain\AbstractMethod;

abstract class AbstractMethodParser extends AbstractParser {

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
	 * @param AbstractMethod $method
	 * @param $data
	 */
	public function setHeaders(AbstractMethod $method, $data) {
		foreach($data as $key => $dat) {
			$parameter = $this->namedParameterParser->parse($dat, $key);
			$method->addHeader($parameter, $key);
		}
	}

	/**
	 * @param AbstractMethod $method
	 * @param $data
	 */
	protected function setQueryParameters(AbstractMethod $method, $data) {
		foreach($data as $name => $parameter) {
			$parameter = $this->namedParameterParser->parse($parameter, $name);
			$method->addQueryParameter($parameter, $name);
		}
	}

	/**
	 * @param AbstractMethod $method
	 * @param $data
	 */
	protected function setResponses(AbstractMethod $method, $data) {
		foreach($data as $statusCode => $conf) {
			$response = $this->responseParser->parse($conf, $statusCode);
			$method->addResponse($response, $statusCode);
		}
	}

	/**
	 * @param AbstractMethod $method
	 * @param $data
	 */
	protected function setBaseUriParameters(AbstractMethod $method, $data) {
		foreach($data as $name => $parameter) {
			$parameter = $this->uriParameterParser->parse($parameter, $name);
			$method->addBaseUriParameter($parameter, $name);
		}
	}
}