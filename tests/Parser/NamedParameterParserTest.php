<?php
namespace Xopn\PhpRamlParserTests\Parser;

use Xopn\PhpRamlParser\Domain\NamedParameter;
use Xopn\PhpRamlParser\Parser\NamedParameterParser;
use Xopn\PhpRamlParserTests\AbstractTest;

class NamedParameterParserTest extends AbstractTest {

	/**
	 * @var NamedParameterParser
	 */
	protected $parser;

	public function setUp() {
		$this->parser = new NamedParameterParser();
	}

	public function testBasic() {
		$data = [
			'type' => 'string',
			'description' => 'Hello World'
		];

		$namedParameter = $this->parser->parse($data, 'key');

		$this->assertInstanceOf('\\Xopn\\PhpRamlParser\\Domain\\NamedParameter', $namedParameter);
		$this->assertSame(NamedParameter::TYPE_STRING, $namedParameter->getType(), 'type should be right');
		$this->assertSame('Hello World', $namedParameter->getDescription(), 'description should match');
	}

	public function testDisplayName() {
		$data = [
			'type' => 'string',
			'description' => 'Hello World'
		];

		$namedParameter = $this->parser->parse($data, 'key');
		$this->assertSame('key', $namedParameter->getDisplayName(), 'key should be default displayName');

		$data['displayName'] = 'bazBar';
		$namedParameter = $this->parser->parse($data, 'key');
		$this->assertSame('bazBar', $namedParameter->getDisplayName(), 'key should be overridden');
	}

	public function getSampleData() {
		$data = [
			'displayName' => 'displayName',
			'description' => 'description',
			'type' => 'string',
			'enum' => ['foo', 'bar'],
			'pattern' => '^[a-zA-Z0-9][-a-zA-Z0-9]*$',
			'minLength' => '3',
			'maxLength' => '42',
			'minimum' => '-123.45',
			'maximum' => '123.45',
			'example' => 'example',
			'repeat' => TRUE,
			'required' => TRUE,
			'default' => 'default'
		];

		return [[(new NamedParameterParser())->parse($data, 'key')]];
	}

	/**
	 * @param NamedParameter $namedParameter
	 * @dataProvider getSampleData
	 */
	public function testDescription(NamedParameter $namedParameter) {
		$this->assertInternalType('string', $namedParameter->getDescription(), 'should be a string');
		$this->assertSame('description', $namedParameter->getDescription(), 'should be right');
	}

	/**
	 * @param NamedParameter $namedParameter
	 * @dataProvider getSampleData
	 */
	public function testType(NamedParameter $namedParameter) {
		$this->assertInternalType('string', $namedParameter->getType(), 'should be a string');
		$this->assertSame('string', $namedParameter->getType(), 'should be right');
	}

	/**
	 * @param NamedParameter $namedParameter
	 * @dataProvider getSampleData
	 */
	public function testEnum(NamedParameter $namedParameter) {
		$this->assertInternalType('array', $namedParameter->getEnum(), 'should be an array');
		$this->assertCount(2, $namedParameter->getEnum(), 'should contain 2 items');
		$this->assertSame(['foo', 'bar'], $namedParameter->getEnum(), 'should be right');
	}

	/**
	 * @param NamedParameter $namedParameter
	 * @dataProvider getSampleData
	 */
	public function testPattern(NamedParameter $namedParameter) {
		$this->assertInternalType('string', $namedParameter->getPattern(), 'should be a string');
		$this->assertSame('/^[a-zA-Z0-9][-a-zA-Z0-9]*$/', $namedParameter->getPattern(), 'should be right');
	}

	/**
	 * @param NamedParameter $namedParameter
	 * @dataProvider getSampleData
	 */
	public function testMinLength(NamedParameter $namedParameter) {
		$this->assertInternalType('integer', $namedParameter->getMinLength(), 'should be an integer');
		$this->assertSame(3, $namedParameter->getMinLength(), 'should be right');
	}

	/**
	 * @param NamedParameter $namedParameter
	 * @dataProvider getSampleData
	 */
	public function testMaxLength(NamedParameter $namedParameter) {
		$this->assertInternalType('integer', $namedParameter->getMaxLength(), 'should be an integer');
		$this->assertSame(42, $namedParameter->getMaxLength(), 'should be right');
	}

	/**
	 * @param NamedParameter $namedParameter
	 * @dataProvider getSampleData
	 */
	public function testMinimum(NamedParameter $namedParameter) {
		$this->assertInternalType('float', $namedParameter->getMinimum(), 'should be a float');
		$this->assertSame(-123.45, $namedParameter->getMinimum(), 'should be right');
	}

	/**
	 * @param NamedParameter $namedParameter
	 * @dataProvider getSampleData
	 */
	public function testMaximum(NamedParameter $namedParameter) {
		$this->assertInternalType('float', $namedParameter->getMaximum(), 'should be a float');
		$this->assertSame(123.45, $namedParameter->getMaximum(), 'should be right');
	}

	/**
	 * @param NamedParameter $namedParameter
	 * @dataProvider getSampleData
	 */
	public function testExample(NamedParameter $namedParameter) {
		$this->assertInternalType('string', $namedParameter->getExample(), 'should be a string');
		$this->assertSame('example', $namedParameter->getExample(), 'should be right');
	}

	/**
	 * @param NamedParameter $namedParameter
	 * @dataProvider getSampleData
	 */
	public function testRepeat(NamedParameter $namedParameter) {
		$this->assertInternalType('boolean', $namedParameter->getRepeat(), 'should be a boolean');
		$this->assertTrue($namedParameter->getRepeat(), 'should be right');
	}

	/**
	 * @param NamedParameter $namedParameter
	 * @dataProvider getSampleData
	 */
	public function testRequired(NamedParameter $namedParameter) {
		$this->assertInternalType('boolean', $namedParameter->getRequired(), 'should be a boolean');
		$this->assertTrue($namedParameter->getRequired(), 'should be right');
	}

	/**
	 * @param NamedParameter $namedParameter
	 * @dataProvider getSampleData
	 */
	public function testDefault(NamedParameter $namedParameter) {
		$this->assertInternalType('string', $namedParameter->getDefault(), 'should be a string');
		$this->assertSame('default', $namedParameter->getDefault(), 'should be right');
	}
}
 