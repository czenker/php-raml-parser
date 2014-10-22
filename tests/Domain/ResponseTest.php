<?php
namespace Xopn\PhpRamlParserTests\Domain;

use Xopn\PhpRamlParser\Domain\NamedParameter;
use Xopn\PhpRamlParser\Domain\Response;
use Xopn\PhpRamlParserTests\AbstractTest;

class ResponseTest extends AbstractTest {

	public function testHeadersAreCaseInsensitive() {
		$header = new NamedParameter('X-Foobar');
		$response = new Response(200);
		$response->addHeader($header, 'X-Foobar');

		$this->assertTrue($response->hasHeader('X-Foobar'), 'X-Foobar');
		$this->assertTrue($response->hasHeader('x-foobar'), 'x-foobar');
		$this->assertTrue($response->hasHeader('X-FOOBAR'), 'X-FOOBAR');

		$this->assertFalse($response->hasHeader('X-Not-Foobar'), 'X-Not-Foobar');
	}

}
 