<?php
namespace Xopn\PhpRamlParserTests\Domain;

use Xopn\PhpRamlParser\Domain\Definition;
use Xopn\PhpRamlParser\Domain\Method;
use Xopn\PhpRamlParser\Domain\Resource;
use Xopn\PhpRamlParserTests\AbstractTest;

class DefinitionTest extends AbstractTest {

	public function testGetAllResources() {
		$definition = new Definition();

		$resource1 = new Resource('/path');
		$definition->addResource($resource1, '/path');
			$resource11 = new Resource('/to');
			$resource1->addResource($resource11, '/to');
		$resource2 = new Resource('/resource');
		$definition->addResource($resource2, '/resource');

		$allResources = $definition->getAllResources();

		$this->assertContains($resource1, $allResources);
		$this->assertContains($resource2, $allResources);
		$this->assertContains($resource11, $allResources);
		$this->assertCount(3, $allResources);
	}

	public function testGetAllMethods() {
		$definition = new Definition();

		$resource1 = new Resource('/path');
		$definition->addResource($resource1, '/path');
			$method11 = new Method('get');
			$resource1->addMethod($method11, 'get');
			$method12 = new Method('post');
			$resource1->addMethod($method12, 'post');
			$resource11 = new Resource('/to');
			$resource1->addResource($resource11, '/to');
				$method111 = new Method('get');
				$resource11->addMethod($method111, 'get');
		$resource2 = new Resource('/other_path');
		$definition->addResource($resource2, '/other_path');
			$method21 = new Method('get');
			$resource2->addMethod($method21, 'get');


		$allMethods = $definition->getAllMethods();

		$this->assertContains($method11, $allMethods);
		$this->assertContains($method12, $allMethods);
		$this->assertContains($method111, $allMethods);
		$this->assertContains($method21, $allMethods);
		$this->assertCount(4, $allMethods);
	}
}
 