<?php
namespace Xopn\PhpRamlParserTests\Resolver;

use Xopn\PhpRamlParser\Domain\Definition;
use Xopn\PhpRamlParser\Domain\Method;
use Xopn\PhpRamlParser\Domain\Resource;
use Xopn\PhpRamlParser\Resolver\Resolver;
use Xopn\PhpRamlParserTests\AbstractTest;

class ResolverTest extends AbstractTest {

	/**
	 * @var Resolver
	 */
	protected $resolver;

	public function setUp() {
		$this->resolver = new Resolver();
	}

	public function testAdditionalDescriptionIsApplied() {
		$definition = $this->getDefinitionForTestWithoutParameters();
		$resource = $definition->getResource('/users');
		$get = $resource->getMethod('get');

		$this->resolver->applyResourceTypesAndTraits($definition);

		$this->assertNotEmpty($get->getDescription(), 'description got applied to get method');
	}

	public function testDescriptionIsNotOverridden() {
		$definition = $this->getDefinitionForTestWithoutParameters();
		$resource = $definition->getResource('/users');
		$get = $resource->getMethod('get');
		$get->setDescription('foobar');

		$this->resolver->applyResourceTypesAndTraits($definition);

		$this->assertSame('foobar', $get->getDescription());
	}

	public function testPostMethodIsCreatedByResourceType() {
		$definition = $this->getDefinitionForTestWithoutParameters();
		$resource = $definition->getResource('/users');

		$this->resolver->applyResourceTypesAndTraits($definition);

		// should not throw exception
		$post = $resource->getMethod('post');
		$this->assertNotEmpty($post->getDescription());
	}

	public function testTraitsFromResourceAreApplied() {
		$definition = $this->getDefinitionForTestWithoutParameters();
		$resource = $definition->getResource('/users');

		$this->resolver->applyResourceTypesAndTraits($definition);

		$get = $resource->getMethod('get');
		$this->assertTrue($get->hasQueryParameter('access_token'), 'trait applied to explicit defined method');
		$this->assertSame('Access Token', $get->getQueryParameter('access_token')->getDescription());

		$post = $resource->getMethod('post');
		$this->assertTrue($post->hasQueryParameter('access_token'), 'trait applied to method defined by resourceType');
		$this->assertSame('Access Token', $post->getQueryParameter('access_token')->getDescription());
	}

	public function testTraitsFromMethodAreApplied() {
		$definition = $this->getDefinitionForTestWithoutParameters();
		$resource = $definition->getResource('/users');
		$get = $resource->getMethod('get');

		$this->resolver->applyResourceTypesAndTraits($definition);

		$this->assertTrue($get->hasQueryParameter('start'), 'should have "start" parameter');
		$this->assertSame('number', $get->getQueryParameter('start')->getType());
		$this->assertTrue($get->hasQueryParameter('query'), 'should have "query" parameter');
		$this->assertSame('string', $get->getQueryParameter('query')->getType());
	}

	public function testResourcePathNameIsReplacedInTrait() {
		$definition = $this->getDefinitionForTestWithoutParameters();
		$resource = $definition->getResource('/users');
		$get = $resource->getMethod('get');

		$this->resolver->applyResourceTypesAndTraits($definition);

		$this->assertStringStartsWith('Get all users', $get->getDescription());
	}

	public function testParameterFilters() {
		$this->markTestIncomplete('not implemented yet');
	}

	public function testWithParameters() {
		$this->markTestIncomplete();
	}

	protected function getDefinitionForTestWithoutParameters() {
		$definition = new Definition();
		$definition->setTitle('Example API');
		$definition->setVersion('v1');
		$definition->addResourceType('collection', [
			'usage' => 'This resourceType should be used for any collection of items',
			'description' => 'The collection of <<resourcePathName>>',
			'get' => [
				'description' => 'Get all <<resourcePathName>>, optionally filtered',
			],
			'post' => [
				'description' => 'Create a new <<resourcePathName | !singularize>>'
			],
		]);
		$definition->addTrait('secured', [
			'usage' => 'Apply this to any method that needs to be secured',
			'description' => 'Some requests require authentication.',
			'queryParameters' => [
				'access_token' => [
					'description' => 'Access Token',
					'type' => 'string',
					'example' => 'ACCESS_TOKEN',
					'required' => TRUE,
				]
			]
		]);
		$definition->addTrait('paged', [
			'queryParameters' => [
				'start' => [
					'type' => 'number'
				]
			],
		]);
		$definition->addTrait('searchable', [
			'queryParameters' => [
				'query' => [
					'type' => 'string'
				]
			]
		]);

		$resource = new Resource('/users');
		$resource->setResourceType('collection');
		$resource->addTrait('secured');

		$get = new Method('get');
		$get->addTrait('paged');
		$get->addTrait('searchable');

		$definition->addResource($resource, '/users');
		$resource->addMethod($get, 'get');
		return $definition;
	}

	public function testParametersInTraitsReplacedInValues() {
		$definition = $this->getDefinitionForTestWithParameters();
		$resource = $definition->getResource('/books');
		$get = $resource->getMethod('get');

		$this->resolver->applyResourceTypesAndTraits($definition);

		$this->assertContains(
			'not to exceed 10',
			$get->getQueryParameter('numPages')->getDescription()
		);
	}

	public function testParametersInTraitsReplacedInKeys() {
		$definition = $this->getDefinitionForTestWithParameters();
		$resource = $definition->getResource('/books');
		$get = $resource->getMethod('get');

		$this->resolver->applyResourceTypesAndTraits($definition);

		$this->assertTrue($get->hasQueryParameter('access_token'));
	}

	public function testParametersInResourceReplacedInKeys() {
		$definition = $this->getDefinitionForTestWithParameters();
		$resource = $definition->getResource('/books');
		$get = $resource->getMethod('get');

		$this->resolver->applyResourceTypesAndTraits($definition);

		$this->assertTrue($get->hasQueryParameter('title'), 'should have "title"');
		$this->assertTrue($get->hasQueryParameter('digest_all_fields'), 'should have "digest_all_fields"');
	}

	public function testParametersInResourceTypeReplacedInValues() {
		$definition = $this->getDefinitionForTestWithParameters();
		$resource = $definition->getResource('/books');
		$get = $resource->getMethod('get');

		$this->resolver->applyResourceTypesAndTraits($definition);

		$this->assertContains(
			'If no values match the value given for title',
			$get->getQueryParameter('digest_all_fields')->getDescription()
		);
	}

	protected function getDefinitionForTestWithParameters() {
		$definition = new Definition();
		$definition->setTitle('Example API');
		$definition->setVersion('v1');
		$definition->addResourceType('searchableCollection', [
			'get' => [
				'queryParameters' => [
					'<<queryParamName>>' => [
						'description' => 'Return <<resourcePathName>> that have their <<queryParamName>> matching the given value',
					],
					'<<fallbackParamName>>' => [
						'description' => 'If no values match the value given for <<queryParamName>>, use <<fallbackParamName>> instead'
					],
				],
			],
		]);
		$definition->addTrait('secured', [
			'queryParameters' => [
				'<<tokenName>>' => [
					'description' => 'A valid <<tokenName>> is required',
				]
			]
		]);
		$definition->addTrait('paged', [
			'queryParameters' => [
				'numPages' => [
					'description' => 'The number of pages to return, not to exceed <<maxPages>>'
				]
			],
		]);

		$resource = new Resource('/books');
		$resource->setResourceType('searchableCollection', [
			'queryParamName' => 'title',
			'fallbackParamName' => 'digest_all_fields'
		]);

		$get = new Method('get');
		$get->addTrait('secured', [
			'tokenName' => 'access_token'
		]);
		$get->addTrait('paged', [
			'maxPages' => 10
		]);

		$definition->addResource($resource, '/books');
		$resource->addMethod($get, 'get');
		return $definition;
	}
}
 