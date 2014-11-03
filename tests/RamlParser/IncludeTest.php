<?php
namespace Xopn\PhpRamlParserTests\RamlParser;

use Xopn\PhpRamlParser\RamlParser;
use Xopn\PhpRamlParserTests\AbstractTest;

class IncludeTest extends AbstractTest {

	/**
	 * @var RamlParser
	 */
	protected $ramlParser;

	public function setUp() {
		$this->ramlParser = new RamlParser();
	}

	public function testIncludingRaml() {
		$definition = $this->ramlParser->parse(__DIR__ . '/fixtures/includeRamlTest.raml');

		$this->assertInstanceOf('\\Xopn\\PhpRamlParser\\Domain\\Definition', $definition);
		$this->assertTrue($definition->hasResource('/users'), 'should have /user resource');

		$resource = $definition->getResource('/users');
		$this->assertInstanceOf('\\Xopn\\PhpRamlParser\\Domain\\Resource', $resource);
		$this->assertSame('retrieve all users', $resource->getDisplayName(), 'included RAML should be parsed as YAML');
	}

	public function testIncludingYaml() {
		$definition = $this->ramlParser->parse(__DIR__ . '/fixtures/includeYamlTest.raml');

		$this->assertInstanceOf('\\Xopn\\PhpRamlParser\\Domain\\Definition', $definition);
		$this->assertTrue($definition->hasResource('/users'), 'should have /user resource');

		$resource = $definition->getResource('/users');
		$this->assertInstanceOf('\\Xopn\\PhpRamlParser\\Domain\\Resource', $resource);
		$this->assertTrue($resource->hasBaseUriParameter('apiDomain'));
	}

	public function testIncludingText() {
		$definition = $this->ramlParser->parse(__DIR__ . '/fixtures/includeTextTest.raml');

		$this->assertInstanceOf('\\Xopn\\PhpRamlParser\\Domain\\Definition', $definition);
		$this->assertCount(1, $definition->getDocumentation());

		$documentation = $definition->getDocumentation()[0];

		$this->assertSame('Lorem Ipsum', $documentation->getContent());
	}

}
 