<?php
namespace Xopn\PhpRamlParserTests\RamlParser;

use Xopn\PhpRamlParser\RamlParser;
use Xopn\PhpRamlParserTests\AbstractTest;

class BasicTest extends AbstractTest {

	/**
	 * @var RamlParser
	 */
	protected $ramlParser;

	public function setUp() {
		$this->ramlParser = new RamlParser();
	}

	public function testBasic() {
		$definition = $this->ramlParser->parse(__DIR__ . '/fixtures/basicRamlParserTest.raml');

		$this->assertInstanceOf('\\Xopn\\PhpRamlParser\\Domain\\Definition', $definition);

		// test a few points
		$this->assertSame('Users API', $definition->getTitle());
		$this->assertCount(1, $definition->getResources());
		$this->assertTrue($definition->hasResource('/users'));

		$resource = $definition->getResource('/users');
		$this->assertSame('retrieve all users', $resource->getDisplayName());
		$this->assertCount(1, $resource->getResources());
		$this->assertTrue($resource->hasResource('/{userId}/image'));

		$resource = $resource->getResource('/{userId}/image');
		$this->assertCount(2, $resource->getMethods());
		$this->assertTrue($resource->hasMethod('put'));

		$method = $resource->getMethod('put');
		$this->assertSame('update a user\'s picture', $method->getDescription());
		$this->assertCount(1, $method->getBaseUriParameters());
		$this->assertTrue($method->hasBaseUriParameter('apiDomain'));

		$parameter = $method->getBaseUriParameter('apiDomain');
		$this->assertSame(['content-update'], $parameter->getEnum());
	}

}
 