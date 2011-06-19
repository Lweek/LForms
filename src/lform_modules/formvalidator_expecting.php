<?php
/**
 * Validate expecting value
 * 
 * @author Vladimir Belohradsky <info@lweek.net>
 * @package LForms
 * @package Lforms\FormValidator
 * @version 1.1 2011-06-19
 */
class FormValidator_Expecting {
	/** @var string $errorMessage */
	protected $errorMessage = NULL;
	/** @var string $expectingValue */
	protected $expectingValue;

	/**
	 * @param $errorMessage string
	 * @param $parameter mixed
	 * @return FormValidator_Length
	 */
	public function __construct($errorMessage = NULL, $parameter = 0) {
		$this->errorMessage = $errorMessage;
		$this->expectingValue = $parameter;
		return $this;
	}

	/**
	 * Check value if match
	 * 
	 * @param $value string
	 * @return string
	 */
	public function check($value) {
		return ($value != $this->expectingValue)? $this->errorMessage: NULL;
	}
}