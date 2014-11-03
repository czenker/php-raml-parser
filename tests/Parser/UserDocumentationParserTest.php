<?php
namespace Xopn\PhpRamlParserTests\Parser;

use Xopn\PhpRamlParser\Parser\UserDocumentationParser;
use Xopn\PhpRamlParserTests\AbstractTest;

class UserDocumentationParserTest extends AbstractTest {

	/**
	 * @var UserDocumentationParser
	 */
	protected $parser;

	public function setUp() {
		$this->parser = new UserDocumentationParser();
	}

	public function testBasic() {
		$userDocumentation = $this->parser->parse(['title' => 'Home']);
		$this->assertInstanceOf('\\Xopn\\PhpRamlParser\\Domain\\UserDocumentation', $userDocumentation);
		$this->assertSame('Home', $userDocumentation->getTitle());
	}

	public function testContent() {
		$userDocumentation = $this->parser->parse(['content' => 'Hello World']);
		$this->assertSame('Hello World', $userDocumentation->getContent());
	}
}
 