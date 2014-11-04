<?php
namespace Xopn\PhpRamlParserTests\Parser;

use Xopn\PhpRamlParser\Parser\MethodTraitParser;
use Xopn\PhpRamlParserTests\AbstractTest;

class MethodTraitParserTest extends AbstractTest {

	/**
	 * @var MethodTraitParser
	 */
	protected $parser;

	public function setUp() {
		$this->parser = new MethodTraitParser();
	}

	public function testBasic() {
		$method = $this->parser->parse([
			'displayName' => 'Example'
		], 'get');
		$this->assertInstanceOf('\\Xopn\\PhpRamlParser\\Domain\\MethodTrait', $method);
		$this->assertSame('Example', $method->getDisplayName());
	}

}