<?php
namespace Xopn\PhpRamlParserTests\Domain;

use Xopn\PhpRamlParser\Domain\Definition;
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

}
 