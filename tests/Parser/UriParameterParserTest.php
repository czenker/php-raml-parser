<?php
namespace Xopn\PhpRamlParserTests\Parser;

use Xopn\PhpRamlParser\Parser\UriParameterParser;
use Xopn\PhpRamlParserTests\AbstractTest;

class UriParameterParserTest extends AbstractTest {

	/**
	 * @var UriParameterParser
	 */
	protected $parser;

	public function setUp() {
		$this->parser = new UriParameterParser();
	}

	public function testBasic() {
		$data = [
			'type' => 'string',
			'description' => 'Hello World'
		];

		$namedParameter = $this->parser->parse($data, 'key');

		$this->assertInstanceOf('\\Xopn\\PhpRamlParser\\Domain\\UriParameter', $namedParameter);
		$this->assertSame('Hello World', $namedParameter->getDescription(), 'description should match');
	}

}
