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

	public function testPropertyKey() {
		$resource = $this->parser->parse([], '/gists');

		$this->assertSame('/gists', $resource->getRelativeUri());
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
		$this->assertSame($resource, $nestedResource->getParent());
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
		$this->assertSame($resource, $parameter->getParent());
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
		$this->assertSame($resource, $method->getParent());
	}

	public function testResourceType() {
		$resource = $this->parser->parse([
			'type' => 'collection',
		], '/example');

		$this->assertSame('collection', $resource->getResourceType());
		$this->assertSame([], $resource->getResourceTypeParameters());
	}

	public function testResourceTypeWithParameters() {
		$resource = $this->parser->parse([
			'type' => [
				'collection' => [ 'foo' => 'bar', 'baz' => 42 ]
			],
		], '/example');

		$this->assertSame('collection', $resource->getResourceType());

		$parameters = $resource->getResourceTypeParameters();
		$this->assertCount(2, $parameters);
		$this->assertArrayHasKey('foo', $parameters);
		$this->assertSame('bar', $parameters['foo']);
		$this->assertArrayHasKey('baz', $parameters);
	}

	public function testTraits() {
		$resource = $this->parser->parse([
			'is' => [
				'secured',
				'foobar' => [ 'baz' => 'bar' ],
			]
		], '/secret');

		$this->assertCount(2, $resource->getTraits());
		$this->assertTrue($resource->hasTrait('secured'), 'should have "secured" trait');
		$this->assertCount(0, $resource->getTraitParameters('secured'), '"secured" trait should have no parameters');

		$this->assertTrue($resource->hasTrait('foobar'), 'should have "foobar" trait');
		$parameters = $resource->getTraitParameters('foobar');
		$this->assertCount(1, $parameters, '"foobar" trait should have one parameter');
		$this->assertArrayHasKey('baz', $parameters);
		$this->assertSame('bar', $parameters['baz']);
	}

}
 