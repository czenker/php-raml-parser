<?php
namespace Xopn\PhpRamlParser\Domain;

class UriParameter extends NamedParameter {

	/**
	 * The required attribute specifies whether the parameter and its value MUST be present in the API definition.
	 *
	 * For a URI parameter, the required attribute MAY be omitted, but its default value is 'true'.
	 * 
	 * @var bool
	 */
	protected $required = TRUE;

} 