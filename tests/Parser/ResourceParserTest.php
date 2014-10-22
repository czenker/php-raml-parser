<?php
namespace Xopn\PhpRamlParserTests\Parser;

use Xopn\PhpRamlParser\Parser\ResourceParser;

class ResourceParserTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var ResourceParser
	 */
	protected $parser;

	public function setUp() {
		$this->parser = new ResourceParser();
	}

	public function testDisplayName() {
		$resource = $this->parser->parse([
			'displayName' => 'Gists'
		], '/gists');

		$this->assertInstanceOf('\\Xopn\\PhpRamlParser\\Domain\\Resource', $resource);
		$this->assertSame('Gists', $resource->getDisplayName());
	}

	public function testDescription() {
		$resource = $this->parser->parse([
			'displayName' => 'Jobs',
			'description' => 'A collection of jobs',
		], '/jobs');

		$this->assertSame('A collection of jobs', $resource->getDescription());
	}

	public function testNestedResources() {
		$resource = $this->parser->parse([
			'displayName' => 'Users',
			'/{userId}' => [
				'displayName' => 'User',
			],
		], '/users');

		$this->assertCount(1, $resource->getResources());
		$this->assertTrue($resource->hasResource('/{userId}'));
		$nestedResource = $resource->getResource('/{userId}');
		$this->assertInstanceOf('\\Xopn\\PhpRamlParser\\Domain\\Resource', $nestedResource);
		$this->assertSame('User', $nestedResource->getDisplayName());
	}

	public function testBaseUriParameters() {
		$resource = $this->parser->parse([
			'displayName' => 'Download files',
			'baseUriParameters' => [
				'apiDomain' => [
					'enum' => ['api-content'],
				],
			]
		], '/files');

		$this->assertCount(1, $resource->getBaseUriParameters());
		$this->assertTrue($resource->hasBaseUriParameter('apiDomain'));
		$parameter = $resource->getBaseUriParameter('apiDomain');
		$this->assertInstanceOf('\\Xopn\\PhpRamlParser\\Domain\\UriParameter', $parameter);
		$this->assertSame(['api-content'], $parameter->getEnum());
	}

	public function testMethods() {
		$resource = $this->parser->parse([
			'methods' => [
				'post' => [
					'description' => 'Create a Job',
				],
				'get' => [
					'description' => 'List Jobs',
				],
			]
		], '/jobs');

		$this->assertCount(2, $resource->getMethods());
		$this->assertTrue($resource->hasMethod('get'));
		$method = $resource->getMethod('get');
		$this->assertInstanceOf('\\Xopn\\PhpRamlParser\\Domain\\Method', $method);
		$this->assertSame('List Jobs', $method->getDescription());
	}

}
 