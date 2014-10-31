<?php
namespace Xopn\PhpRamlParserTests\Domain;

use Xopn\PhpRamlParser\Domain\Definition;
use Xopn\PhpRamlParser\Domain\Method;
use Xopn\PhpRamlParser\Domain\Resource;

class ResourceTest extends \PHPUnit_Framework_TestCase {

	public function testFullRelativeUri() {
		$definition = new Definition();
		$resource1 = new Resource('/path');
		$resource2 = new Resource('/to');
		$resource3 = new Resource('/resource');

		$definition->addResource($resource1, '/path');
		$resource1->addResource($resource2, '/to');
		$resource2->addResource($resource3, '/resource');

		$this->assertSame(
			'/path',
			$resource1->getFullRelativeUri(),
			'full relative uri for root resource should be right'
		);

		$this->assertSame(
			'/path/to/resource',
			$resource3->getFullRelativeUri(),
			'full relative uri for nested resource should be right'
		);

	}

	public function testGetAllResources() {
		$resource = new Resource('/path');
			$resource1 = new Resource('/to');
			$resource->addResource($resource1, '/to');
				$resource11 = new Resource('/resource');
				$resource1->addResource($resource11, '/resource');
				$resource12 = new Resource('/other_resource');
				$resource1->addResource($resource12, '/other_resource');
			$resource2 = new Resource('/resource');
			$resource->addResource($resource2, '/resource');

		$allResources = $resource->getAllResources();

		$this->assertContains($resource1, $allResources);
		$this->assertContains($resource11, $allResources);
		$this->assertContains($resource12, $allResources);
		$this->assertContains($resource2, $allResources);
		$this->assertCount(4, $allResources);
	}

	public function testGetAllMethods() {
		$resource = new Resource('/path');
			$method1 = new Method('get');
			$resource->addMethod($method1, 'get');
			$method2 = new Method('post');
			$resource->addMethod($method2, 'post');
			$resource1 = new Resource('/to');
			$resource->addResource($resource1, '/to');
				$resource11 = new Resource('/resource');
				$resource1->addResource($resource11, '/resource');
					$method111 = new Method('get');
					$resource11->addMethod($method111, 'get');
				$resource12 = new Resource('/other_resource');
				$resource1->addResource($resource12, '/other_resource');
					$method112 = new Method('post');
					$resource12->addMethod($method112, 'post');
			$resource2 = new Resource('/resource');
			$resource->addResource($resource2, '/resource');
				$method21 = new Method('put');
				$resource2->addMethod($method21, 'put');
				$method22 = new Method('patch');
				$resource2->addMethod($method22, 'patch');

		$allMethods = $resource->getAllMethods();

		$this->assertContains($method1, $allMethods);
		$this->assertContains($method2, $allMethods);
		$this->assertContains($method111, $allMethods);
		$this->assertContains($method112, $allMethods);
		$this->assertContains($method21, $allMethods);
		$this->assertContains($method22, $allMethods);
		$this->assertCount(6, $allMethods);
	}

}
 