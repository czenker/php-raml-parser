<?php
namespace Xopn\PhpRamlParserTests\Parser;

use Symfony\Component\Yaml\Yaml;
use Xopn\PhpRamlParser\Parser\DefinitionParser;
use Xopn\PhpRamlParserTests\AbstractTest;

class DefinitionParserTest extends AbstractTest {

	/**
	 * @var DefinitionParser
	 */
	protected $parser;

	public function setUp() {
		$this->parser = new DefinitionParser();
	}

	protected function getFixture($path) {
		return parent::getFixture(__DIR__ . '/examples/' . $path);
	}

	public function testBasic() {
		$definition = $this->parser->parse([
			'title' => 'GitHub API',
		]);
		$this->assertInstanceOf('\\Xopn\\PhpRamlParser\\Domain\\Definition', $definition);
		$this->assertSame('GitHub API', $definition->getTitle());
	}

	public function testVersion() {
		$definition = $this->parser->parse([
			'title' => 'GitHub API',
			'version' => 'v3',
		]);
		$this->assertSame('v3', $definition->getVersion());
	}

	public function testBaseUri() {
		$definition = $this->parser->parse([
			'title' => 'GitHub API',
			'baseUri' => 'https://api.github.com',
		]);
		$this->assertSame('https://api.github.com', $definition->getBaseUri());
	}

	public function testTeamplateBaseUri() {
		$definition = $this->parser->parse([
			'title' => 'Salesforce Chatter REST API',
			'version' => 'v28.0',
			'baseUri' => 'https://na1.salesforce.com/services/data/{version}/chatter',
		]);
		$this->assertSame('https://na1.salesforce.com/services/data/{version}/chatter', $definition->getBaseUri());
	}

	public function testProtocols() {
		$definition = $this->parser->parse([
			'title' => 'Salesforce Chatter REST API',
			'protocols' => ['HTTP', 'HTTPS'],
		]);
		$this->assertSame(['HTTP', 'HTTPS'], $definition->getProtocols());
	}

	public function testDefaultMediaType() {
		$definition = $this->parser->parse([
			'title' => 'Stormpath REST API',
			'mediaType' => 'application/json',
		]);
		$this->assertSame('application/json', $definition->getMediaType());
	}

	public function testSchemas() {
		$definition = $this->parser->parse([
			'title' => 'Filesystem API',
			'schemas' => [
				[
					'Foo' => '{...}',
					'Bar' => '{...}',
					'Baz' => '{...}',
				],
				[
					'File' => '{...}',
					'FileUpdate' => '{...}',
					'Files' => '{...}',
					'Dir' => '{...}',
					'Dirs' => '{...}',
				]
			]
		]);
		$this->assertCount(8, $definition->getSchemas());
		$this->assertTrue($definition->hasSchema('Foo'));
		$this->assertSame('{...}', $definition->getSchema('Foo'));

		$this->assertFalse($definition->hasSchema('Foobar'));
	}

	public function testBaseUriParameters() {
		$definition = $this->parser->parse([
			'title' => 'Dropbox API',
			'baseUri' => 'https://{apiDomain}.dropbox.com/{version}',
			'baseUriParameters' => [
				'apiDomain' => [
					'description' => 'The sub-domain at which the API is accessible. Most API calls are sent to https://api.dropbox.com',
					'enum' => ['api'],
				],
			]
		]);

		$this->assertCount(1, $definition->getBaseUriParameters());
		$this->assertTrue($definition->hasBaseUriParameter('apiDomain'));

		$parameter = $definition->getBaseUriParameter('apiDomain');
		$this->assertInstanceOf('\\Xopn\\PhpRamlParser\\Domain\\UriParameter', $parameter);
		$this->assertStringStartsWith('The sub-domain at which the API is accessible', $parameter->getDescription());
		$this->assertSame($definition, $parameter->getParent());
	}

	public function testUriParameters() {
		$definition = $this->parser->parse([
			'title' => 'Salesforce Chatter Communities REST API',
			'baseUri' => 'https://{communityDomain}.force.com/{communityPath}',
			'uriParameters' => [
				'communityDomain' => [
					'displayName' => 'Community Domain',
					'type' => 'string',
				],
				'communityPath' => [
					'displayName' => 'Community Path',
					'type' => 'string',
					'pattern' => '^[a-zA-Z0-9][-a-zA-Z0-9]*$',
					'minLength' => 1
				]
			]
		]);

		$this->assertCount(2, $definition->getUriParameters());
		$this->assertTrue($definition->hasUriParameter('communityPath'));

		$parameter = $definition->getUriParameter('communityPath');
		$this->assertInstanceOf('\\Xopn\\PhpRamlParser\\Domain\\UriParameter', $parameter);
		$this->assertSame('Community Path', $parameter->getDisplayName());
		$this->assertSame($definition, $parameter->getParent());
	}

	public function testDocumentation() {
		$definition = $this->parser->parse([
			'title' => 'ZEncoder API',
			'documentation' => [
				[
					'title' => 'Home',
					'content' => 'Welcome to the _Zencoder API_ Documentation. The _Zencoder API_...'
				],
				[
					'title' => 'Getting Started',
					'content' => 'Lorem ispum',
				]
			],
		]);

		$this->assertCount(2, $definition->getDocumentation());
		foreach($definition->getDocumentation() as $userDoc) {
			$this->assertInstanceOf('\\Xopn\\PhpRamlParser\\Domain\\UserDocumentation', $userDoc);
			$this->assertSame($definition, $userDoc->getParent());
		}
		$this->assertSame('Home', $definition->getDocumentation()[0]->getTitle());
	}

	public function testTraits() {
		$definition = $this->parser->parse([
			'title' => 'GitHub API',
			'traits' => [
				[
					'secured' => [
						'usage' => 'Apply this to any method that needs to be secured'
					],
					'paged' => [
						'usage' => 'For paged responses'
					],
				],
				[
					'searchable' => 'Resource is searchable'
				],
			],
		]);

		$this->assertCount(3, $definition->getTraits());
		$this->assertTrue($definition->hasTrait('secured'), 'should have "secured" trait');
		$this->assertTrue($definition->hasTrait('paged'), 'should have "paged" trait');
		$this->assertTrue($definition->hasTrait('searchable'), 'should have "searchable" trait');

		$secured = $definition->getTrait('secured');
		$this->assertInternalType('array', $secured);
		$this->assertSame('Apply this to any method that needs to be secured', $secured['usage']);
	}

	public function testResourceTypes() {
		$definition = $this->parser->parse([
			'title' => 'GitHub API',
			'resourceTypes' => [
				[
					'collection' => [
						'usage' => 'This resourceType should be used for any collection of items'
					],
					'member' => [
						'usage' => 'member'
					],
				],
				[
					'specialCollection' => 'specialCollection'
				],
			],
		]);

		$this->assertCount(3, $definition->getResourceTypes());
		$this->assertTrue($definition->hasResourceType('collection'), 'should have "collection" resourceType');
		$this->assertTrue($definition->hasResourceType('member'), 'should have "collection" resourceType');
		$this->assertTrue($definition->hasResourceType('specialCollection'), 'should have "collection" resourceType');

		$collection = $definition->getResourceType('collection');
		$this->assertInternalType('array', $collection);
		$this->assertSame('This resourceType should be used for any collection of items', $collection['usage']);
	}




}
