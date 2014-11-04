<?php
namespace Xopn\PhpRamlParserTests\Parser;

use Xopn\PhpRamlParser\Parser\ResourceTypeParser;

class ResourceTypeParserTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var ResourceTypeParser
	 */
	protected $parser;

	public function setUp() {
		$this->parser = new ResourceTypeParser();
	}

	public function testDisplayName() {
		$resourceType = $this->parser->parse([
			'displayName' => 'Gists'
		], '/gists');

		$this->assertInstanceOf('\\Xopn\\PhpRamlParser\\Domain\\ResourceType', $resourceType);
		$this->assertSame('Gists', $resourceType->getDisplayName());
	}

	public function testPropertyKey() {
		$resourceType = $this->parser->parse([], 'gists');

		$this->assertSame('gists', $resourceType->getName());
	}

	public function testDescription() {
		$resourceType = $this->parser->parse([
			'displayName' => 'Jobs',
			'description' => 'A collection of jobs',
		], '/jobs');

		$this->assertSame('A collection of jobs', $resourceType->getDescription());
	}

	public function testUsage() {
		$resourceType = $this->parser->parse([
			'displayName' => 'Jobs',
			'usage' => 'use everywhere'
		], '/jobs');

		$this->assertSame('use everywhere', $resourceType->getUsage());
	}

	public function testMethods() {
		$resourceType = $this->parser->parse([
			'methods' => [
				'post' => [
					'description' => 'Create a Job',
				],
				'get' => [
					'description' => 'List Jobs',
				],
			]
		], '/jobs');

		$this->assertCount(2, $resourceType->getMethods());
		$this->assertTrue($resourceType->hasMethod('get'));

		$method = $resourceType->getMethod('get');
		$this->assertInstanceOf('\\Xopn\\PhpRamlParser\\Domain\\Method', $method);
		$this->assertSame('List Jobs', $method->getDescription());
		$this->assertSame($resourceType, $method->getParent());
	}
}
 