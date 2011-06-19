<?php
/**
 * Validate char lenght of value
 *
 * @author Vladimir Belohradsky <info@lweek.net>
 * @package LForms
 * @package Lforms\FormValidator
 * @version 1.1 2011-06-19
 */
class FormValidator_Length {
	/** @var string $errorMessage */
	protected $errorMessage = 'This value isn\'t long enought';
	/** @var string $errorMessageAlt */
	protected $errorMessageAlt = 'This value lenght isn\'t in range';
	/** @var int $maxLenght */
	protected $maxLenght;
	/** @var int $minLength */
	protected $minLenght = NULL;

	/**
	 * @param string $errorMessage
	 * @param mixed $parameter
	 * @return FormValidator_Length
	 */
	public function __construct($errorMessage = NULL, $parameter = 0) {
		if (is_array($parameter)) {
			$this->minLength = (int) $parameter[0];
			$this->maxLength = (int) $parameter[1];
		} else {
			$this->maxLength = (int) $parameter;
		}
		if ($errorMessage) {
			$this->errorMessage = $errorMessage;
			$this->errorMessageAlt = $errorMessage;
		}
		return $this;
	}

	/**
	 * Check value if match
	 *
	 * @param string $value
	 * @return string
	 */
	public function check($value) {
		$error = NULL;
		$length = strlen($value);
		if ($this->minLenght && $length < $this->minLength) {
			$error = $this->errorMessageAlt;
		}
		elseif ($length > $this->maxLength) {
			$error = $this->errorMessage;
		}
		return $error;
	}
}