<?php
namespace Xopn\PhpRamlParser\Domain;

class NamedParameter extends AbstractDomain {

	const TYPE_STRING  = 'string';
	const TYPE_NUMBER  = 'number';
	const TYPE_INTEGER = 'integer';
	const TYPE_DATE    = 'date';
	const TYPE_BOOLEAN = 'boolean';
	const TYPE_FILE    = 'file';

	/**
	 * @var string
	 */
	protected $propertyName;

	/**
	 * The displayName attribute specifies the parameter's display name.
	 *
	 * It is a friendly name used only for display or documentation purposes.
	 * If displayName is not specified, it defaults to the property's key (the name of the property itself).
	 *
	 * @var string
	 */
	protected $displayName;

	/**
	 * The description attribute describes the intended use or meaning of the parameter.
	 *
	 * This value MAY be formatted using Markdown [MARKDOWN].
	 * @var string|NULL
	 */
	protected $description = NULL;

	/**
	 * The type attribute specifies the primitive type of the parameter's resolved value.
	 *
	 * @var string
	 */
	protected $type = self::TYPE_STRING;

	/**
	 * The enum attribute provides an enumeration of the parameter's valid values.
	 *
	 * @var array
	 */
	protected $enum;

	/**
	 * The pattern attribute is a regular expression that a parameter of type string MUST match.
	 *
	 * Regular expressions MUST follow the regular expression specification from ECMA 262/Perl 5.
	 *
	 * @var string
	 */
	protected $pattern;

	/**
	 * The minLength attribute specifies the parameter value's minimum number of characters.
	 *
	 * @var integer
	 */
	protected $minLength;

	/**
	 * The maxLength attribute specifies the parameter value's maximum number of characters.
	 *
	 * @var integer
	 */
	protected $maxLength;

	/**
	 * The minimum attribute specifies the parameter's minimum value.
	 *
	 * @var float
	 */
	protected $minimum;

	/**
	 * The maximum attribute specifies the parameter's maximum value.
	 *
	 * @var float
	 */
	protected $maximum;

	/**
	 * The example attribute shows an example value for the property.
	 *
	 * @var mixed
	 */
	protected $example;

	/**
	 * The repeat attribute specifies that the parameter can be repeated.
	 *
	 * @var bool
	 */
	protected $repeat = FALSE;

	/**
	 * The required attribute specifies whether the parameter and its value MUST be present in the API definition.
	 *
	 * @var bool
	 */
	protected $required = FALSE;

	/**
	 * The default attribute specifies the default value to use for the property if the property
	 * is omitted or its value is not specified.
	 *
	 * @var string
	 */
	protected $default;

	/**
	 * @param string $propertyName
	 */
	public function __construct($propertyName) {
		$this->propertyName = $propertyName;
	}

	/**
	 * @return string
	 */
	public function getPropertyName() {
		return $this->propertyName;
	}

	/**
	 * @param string $propertyName
	 */
	public function setPropertyName($propertyName) {
		$this->propertyName = $propertyName;
	}

	/**
	 * @return string
	 */
	public function getDefault() {
		return $this->default;
	}

	/**
	 * @param string $default
	 */
	public function setDefault($default) {
		$this->default = $default;
	}

	/**
	 * @return NULL|string
	 */
	public function getDescription() {
		return $this->description;
	}

	/**
	 * @param NULL|string $description
	 */
	public function setDescription($description) {
		$this->description = $description ? (string)$description : NULL;
	}

	/**
	 * @return string
	 */
	public function getDisplayName() {
		return $this->displayName;
	}

	/**
	 * @param string $displayName
	 */
	public function setDisplayName($displayName) {
		if(empty($displayName) || !is_string($displayName)) {
			throw new \InvalidArgumentException('displayName must not be empty');
		}
		$this->displayName = $displayName;
	}

	/**
	 * @return array
	 */
	public function getEnum() {
		return $this->enum;
	}

	/**
	 * @param array $enum
	 */
	public function setEnum($enum) {
		if($enum !== NULL && !is_array($enum)) {
			throw new \InvalidArgumentException('enum needs to be an array');
		}
		$this->enum = $enum;
	}

	/**
	 * @return mixed
	 */
	public function getExample() {
		return $this->example;
	}

	/**
	 * @param mixed $example
	 */
	public function setExample($example) {
		$this->example = $example;
	}

	/**
	 * @return int
	 */
	public function getMaxLength() {
		return $this->maxLength;
	}

	/**
	 * @param int $maxLength
	 */
	public function setMaxLength($maxLength = NULL) {
		$this->maxLength = $maxLength === NULL ? NULL : intval($maxLength);
	}

	/**
	 * @return float
	 */
	public function getMaximum() {
		return $this->maximum;
	}

	/**
	 * @param float $maximum
	 */
	public function setMaximum($maximum = NULL) {
		$this->maximum = $maximum === NULL ? NULL : floatval($maximum);
	}

	/**
	 * @return int
	 */
	public function getMinLength() {
		return $this->minLength;
	}

	/**
	 * @param int $minLength
	 */
	public function setMinLength($minLength = NULL) {
		$this->minLength = $minLength === NULL ? NULL : intval($minLength);
	}

	/**
	 * @return float
	 */
	public function getMinimum() {
		return $this->minimum;
	}

	/**
	 * @param float $minimum
	 */
	public function setMinimum($minimum = NULL) {
		$this->minimum = $minimum === NULL ? NULL : floatval($minimum);
	}

	/**
	 * @return string
	 */
	public function getPattern() {
		return $this->pattern;
	}

	/**
	 * @param string $pattern
	 */
	public function setPattern($pattern = NULL) {
		if($pattern !== NULL) {
			$pattern = '/' . str_replace('/', '\\/', $pattern) . '/';
			if (preg_match($pattern, '') === FALSE) {
				throw new \InvalidArgumentException(sprintf('The given pattern %s seems invalid.', $pattern));
			}
		}
		$this->pattern = $pattern;
	}

	/**
	 * @return boolean
	 */
	public function getRepeat() {
		return $this->repeat;
	}

	/**
	 * @param boolean $repeat
	 */
	public function setRepeat($repeat = NULL) {
		$this->repeat = $repeat === NULL ? NULL : !!$repeat;
	}

	/**
	 * @return boolean
	 */
	public function getRequired() {
		return $this->required;
	}

	/**
	 * @param boolean $required
	 */
	public function setRequired($required = NULL) {
		$this->required = $required === NULL ? NULL : !!$required;
	}

	/**
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * @param string $type
	 */
	public function setType($type) {
		$this->type = $type;
	}

} 