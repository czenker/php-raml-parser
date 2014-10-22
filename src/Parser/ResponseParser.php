<?php
namespace Xopn\PhpRamlParser\Parser;

use Xopn\PhpRamlParser\Domain\Response;

class ResponseParser extends AbstractParser {

	protected $className = '\\Xopn\\PhpRamlParser\\Domain\\Response';

	/**
	 * @var NamedParameterParser
	 */
	protected $namedParameterParser;

	public function __construct() {
		// @TODO: DI
		$this->namedParameterParser = new NamedParameterParser();
	}

	/**
	 * @param array $data
	 * @param string $objectKey
	 * @return Response
	 */
	public function parse($data, $objectKey = NULL) {
		return parent::parse($data, $objectKey);
	}

	public function setHeaders(Response $response, $data) {
		foreach($data as $key => $dat) {
			$parameter = $this->namedParameterParser->parse($dat, $key);
			$parameter->setParent($response);
			$response->addHeader($parameter, $key);
		}
	}
}