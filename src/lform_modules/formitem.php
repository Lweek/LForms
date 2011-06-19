<?php
/**
 * Abstract Class representing form item
 * 
 * @author Vladimir Belohradsky <info@lweek.net>
 * @package LForms
 * @subpackage LForms\FormItem
 * @version 1.3 2011-06-19
 * @abstract
 */
abstract class FormItem {
	/** @var string $name */
	protected $name;
	/** @var string $value */
	protected $value = NULL;
	/** @var string $defaultValue */
	protected $defaultValue = NULL;
	/** @var string $label */
	protected $label;
	/** @var string $description */
	protected $description = NULL;
	/** @var array $validators */
	protected $validators = NULL;
	/** @var array $errors */
	protected $errors = NULL;
	/** @var bool $required */
	protected $required = FALSE;
	/** @var array $emptyValues */
	protected $emptyValues = array('');
	/** @var string $dependent */
	protected $dependent = NULL;
	/** @var string $readonly */
	protected $readonly = FALSE;
	/** @var string $messageEmpty */
	protected $messageEmpty = 'Can\'t be empty!';

	/**
	 * @param string $name
	 * @param bool $required
	 * @return void
	 */
	public function __construct($name, $required = FALSE) {
		$this->name = (string) $name;
		$this->required = ($required)? TRUE: FALSE;
	}

	/**
	 * Set if formitem is readonly
	 * 
	 * @param bool $status
	 * @return FormItem
	 */
	public function readonly($status = TRUE) {
	    $this->readonly = ($status === TRUE)? TRUE: FALSE;
		return $this;
	}

	/**
	 * Return item name
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Set dependency
	 * 
	 * @param string $name
	 * @return	FormItem
	 */
	public function setDependency($name) {
		$this->dependent = $name;
		return $this;
	}

	/**
	 *	Return name of item which this item is dependent to	
	 * 
	 *	@return string
	 */
	public function getDependency() {
		return $this->dependent;
	}

	/**
	 * Handle value from input
	 * 
	 * @param string $value
	 * @return	FormItem
	 */
	public function loadValue($value) {
		if (!in_array($value, $this->emptyValues)) $this->value = $value;
		return $this;
	}

	/**
	 * Set item default value if post empty
	 * 
	 * @param string $value
	 * @return FormItem
	 */
	public function setDefaultValue($value) {
		$this->defaultValue = (string) $value;
		return $this;
	}

	/**
	 * Set item value
	 * 
	 * @param string $value
	 * @return FormItem
	 */
	public function setValue($value) {
		$this->value = $value;
		return $this;
	}
	
	/**
	 * Get item value
	 * 
	 * @return mixed
	 */
	public function getValue() { 
		return (string) ($this->isEmpty())? 
			(($this->defaultValue)? $this->defaultValue: NULL): $this->value;
	}

	/**
	 * Check if form item value is empty
	 * 
	 * @return bool
	 */
	public function isEmpty() {
		return (in_array($this->value, $this->emptyValues) || !$this->value)? 
			TRUE: FALSE;
	}

	/**
	 * Set item label and eventualy item description
	 * 
	 * @param string $label
	 * @param string $description
	 * @return	FormItem
	 */
	public function setLabel($label, $description = NULL) {
		$this->label = $label;
		$this->description = $description;
		return $this;
	}

	/**
	 * Print label for item
	 * 
	 * @return string
	 */
	public function label() {
		$description = ($this->description)?
			'<span>' . $this->description . '</span>': '';
		return '<label for="' . $this->name . '">' . $this->label .
			$description . '</label>';
	}
	
	/**
	 * Print errors
	 * 
	 * @return string
	 */
	public function errors() {
		$errors = '';
		if ($this->errors) {
			foreach ($this->errors as $error) {
				$errors .= '<span>' . $error . '</span>';
			}
			$errors = '<div class="lform-item-errors">' . $errors . '</div>';
		}
		return $errors;
	}

	/**
	 * Add Values which is going to be interpreted as NULL
	 * 
	 * @param string $value
	 * @return FormItem
	 */
	public function addEmptyValue($value) {
		if (is_array($value)) { 
			$this->emptyValues = array_merge($this->emptyValues, $value);
		} else {
			$this->emptyValues[] = $value;
		}
		return $this;
	}

	/**
	 * Add Validator
	 * 
	 * @param string $validatorName
	 * @param string $errorMessage
	 * @return FormItem
	 */
	public function addValidator(
		$validatorName, $errorMessage = NULL, $parameter = NULL
	) {
		$validatorName = 'FormValidator_' . ucfirst($validatorName);
		$this->validators[] = new $validatorName($errorMessage, $parameter);
		return $this;
	}

	/**
	 * Check all validators
	 * 
	 * @return array
	 */
	public function validate() {
		// if not required but empty then valid
		if (!$this->required && $this->isEmpty()) return NULL;
		// if required but empty then invalid
		if ($this->required && $this->isEmpty()) {
			$this->errors[] = $this->emptyMessage;
		} else { // if required and not empty then check validators
			if (!$this->validators || $this->isEmpty()) return NULL;
			foreach ($this->validators as &$validator) {
				$error = $validator->check($this->value);
				if ($error) $this->errors[] = $error;
			}
		}
		return $this->errors;
	}

	/**
	 * Override required parameter
	 * 
	 * @param bool $required
	 * @return FormItem
	 */
	public function required($required = TRUE) {
		$this->required = ($required)? TRUE: FALSE;
		return $this;
	}

	/**
	 * Check if item is required
	 * 
	 * @return bool
	 */
	public function isRequired() {
		return $this->required;
	}

	/**
	 * Override "can't be empty" message
	 * 
	 * @param string $message
	 * @return FormItem
	 */
	public function setEmptyMessage($message) {
	    $this->emptyMessage = $message;
		return $this;
	}
}