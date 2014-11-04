<?php
namespace Xopn\PhpRamlParser\Resolver;

use Xopn\PhpRamlParser\Domain\AbstractMethod;
use Xopn\PhpRamlParser\Domain\AbstractResource;

class Merger {

	/**
	 * @param AbstractResource $source
	 * @param AbstractResource $into
	 */
	public function mergeResources(AbstractResource $source, AbstractResource $into) {
		// @TODO: add elegance :)
		if(!$into->getDisplayName()) {
			$into->setDisplayName($source->getDisplayName());
		}
		if(!$into->getDescription()) {
			$into->setDescription($source->getDescription());
		}
		// @TODO: uriParameter
		foreach($source->getBaseUriParameters() as $name => $baseUriParameter) {
			if(!$into->hasBaseUriParameter($name)) {
				$into->addBaseUriParameter($baseUriParameter, $name);
			}
		}
		foreach($source->getMethods() as $name => $method) {
			if(!$into->hasMethod($name)) {
				$into->addMethod($method, $name);
			} else {
				$this->mergeMethods($method, $into->getMethod($name));
			}
		}
	}

	/**
	 * @param AbstractMethod $source
	 * @param AbstractMethod $into
	 */
	public function mergeMethods(AbstractMethod $source, AbstractMethod $into) {
		// @TODO: add elegance :)
		if(!$into->getMethod()) {
			$into->setMethod($source->getMethod());
		}
		if(!$into->getDisplayName()) {
			$into->setDisplayName($source->getDisplayName());
		}
		if(!$into->getDescription()) {
			$into->setDescription($source->getDescription());
		}
		if(!$into->getProtocols()) {
			$into->setProtocols($source->getProtocols());
		}
		// @TODO: will headers, etc be merged too?
		foreach($source->getHeaders() as $name => $header) {
			if(!$into->hasHeader($name)) {
				$into->addHeader($header, $name);
			}
		}
		foreach($source->getQueryParameters() as $name => $queryParameter) {
			if(!$into->hasQueryParameter($name)) {
				$into->addQueryParameter($queryParameter, $name);
			}
		}
		foreach($source->getBaseUriParameters() as $name => $baseUriParameter) {
			if(!$into->hasBaseUriParameter($name)) {
				$into->addBaseUriParameter($baseUriParameter, $name);
			}
		}
		// @TODO: body
		foreach($source->getResponses() as $name => $response) {
			if(!$into->hasResponse($response)) {
				$into->addResponse($response, $name);
			}
		}
	}
} 