<?php
namespace Xopn\PhpRamlParserTests\Parser;

use Xopn\PhpRamlParser\Domain\NamedParameter;
use Xopn\PhpRamlParser\Parser\MethodParser;
use Xopn\PhpRamlParserTests\AbstractTest;

class MethodParserTest extends AbstractTest {

	/**
	 * @var MethodParser
	 */
	protected $parser;

	public function setUp() {
		$this->parser = new MethodParser();
	}

	public function testBasic() {
		$method = $this->parser->parse([
			'displayName' => 'Example'
		], 'get');
		$this->assertInstanceOf('\\Xopn\\PhpRamlParser\\Domain\\Method', $method);
		$this->assertSame('Example', $method->getDisplayName());
	}

	public function testDescription() {
		$method = $this->parser->parse([
			'description' => 'List Jobs'
		], 'get');
		$this->assertSame('List Jobs', $method->getDescription());
	}

	public function testHeaders() {
		$method = $this->parser->parse([
			'description' => 'Create a Job',
			'headers' => [
				'Zencoder-Api-Key' => [
					'displayName' => 'ZEncoder API Key',
				]
			]
		], 'post');

		$this->assertCount(1, $method->getHeaders());
		$this->assertTrue($method->hasHeader('Zencoder-Api-Key'));
		$this->assertInstanceOf('\\Xopn\\PhpRamlParser\\Domain\\NamedParameter', $method->getHeader('Zencoder-Api-Key'));
		$this->assertSame('ZEncoder API Key',$method->getHeader('Zencoder-Api-Key')->getDisplayName());
	}

	public function testHeaderWildcardSupport() {
		$this->markTestIncomplete('TODO: Not sure if this can be even done properly...');
	}

	public function testProtocols() {
		$method = $this->parser->parse([
			'description' => 'Returns a collection of relevant Tweets matching a specified query',
			'protocols' => ['HTTP', 'HTTPS'],
		], 'get');
		$this->assertSame(['HTTP', 'HTTPS'], $method->getProtocols());
	}

	public function testQueryParameters() {
		$method = $this->parser->parse([
			'description' => 'Get a list of users',
			'queryParameters' => [
				'page' => [
					'type' => 'integer',
				],
				'per_page' => [
					'type' => 'integer',
				]
			]
		], 'get');

		$this->assertCount(2, $method->getQueryParameters());
		$this->assertTrue($method->hasQueryParameter('page'));
		$parameter = $method->getQueryParameter('page');
		$this->assertInstanceOf('\\Xopn\\PhpRamlParser\\Domain\\NamedParameter', $parameter);
		$this->assertSame(NamedParameter::TYPE_INTEGER, $parameter->getType());
	}

	public function testBody() {
		$this->markTestIncomplete('TODO');
	}

	public function testResponses() {
		$method = $this->parser->parse([
			'description' => 'Get a list of what media is most popular at the moment.',
			'responses' => [
				'503' => [
					'description' => 'The service is currently unavailable',
				],
			]
		], 'get');

		$this->assertCount(1, $method->getResponses());
		$this->assertTrue($method->hasResponse('503'));
		$response = $method->getResponse('503');
		$this->assertInstanceOf('\\Xopn\\PhpRamlParser\\Domain\\Response', $response);
		$this->assertSame('The service is currently unavailable', $response->getDescription());
	}

	public function testBaseUriParameters() {
		$method = $this->parser->parse([
			'displayName' => 'update a user\'s picture',
			'baseUriParameters' => [
				'apiDomain' => [
					'enum' => ['content-update'],
				],
			]
		], '/put');

		$this->assertCount(1, $method->getBaseUriParameters());
		$this->assertTrue($method->hasBaseUriParameter('apiDomain'));
		$parameter = $method->getBaseUriParameter('apiDomain');
		$this->assertInstanceOf('\\Xopn\\PhpRamlParser\\Domain\\UriParameter', $parameter);
		$this->assertSame(['content-update'], $parameter->getEnum());
	}
}
 