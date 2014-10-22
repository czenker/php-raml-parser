<?php
namespace Xopn\PhpRamlParserTests\Parser;

use Xopn\PhpRamlParser\Parser\ResponseParser;

class ResponseParserTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var ResponseParser
	 */
	protected $parser;

	public function setUp() {
		$this->parser = new ResponseParser();
	}

	public function testBasic() {
		$response = $this->parser->parse([
			'description' => 'The service is currently unavailable'
		], 503);

		$this->assertInstanceOf('\\Xopn\\PhpRamlParser\\Domain\\Response', $response);
		$this->assertSame(503, $response->getStatusCode());
		$this->assertSame('The service is currently unavailable', $response->getDescription());
	}

	public function testBody() {
		$this->markTestIncomplete('TODO');
	}

	public function testHeaders() {
		$response = $this->parser->parse([
			'description' => 'The service is currently unavailable',
			'headers' => [
				'X-waiting-period' => [
					'description' => 'The number of seconds to wait before you can attempt to make a request again.',
					'type' => 'integer',
				]
			]
		], 503);

		$this->assertCount(1, $response->getHeaders());
		$this->assertTrue($response->hasHeader('X-waiting-period'));
		$this->assertInstanceOf('\\Xopn\\PhpRamlParser\\Domain\\NamedParameter', $response->getHeader('X-waiting-period'));
		$this->assertSame(
			'The number of seconds to wait before you can attempt to make a request again.',
			$response->getHeader('X-waiting-period')->getDescription()
		);
	}

}
 