<?php
/**
 * Validate value as email adress
 *
 * @author Vladimir Belohradsky <info@lweek.net>
 * @package LForms
 * @package Lforms\FormValidator
 * @version 1.1 2011-06-19
 */
class FormValidator_Email {
	/** @var string $errorMessage */
	protected $errorMessage = 'This should be a valid email adress'; // string
	
	/**
	 * @param $errorMessage string
	 * @param $parameter mixed
	 * @return FormValidator_Int
	 */
	public function __construct($errorMessage = NULL, $parameter = NULL) {
		if ($errorMessage) $this->errorMessage = $errorMessage;
		return $this;
	}

	/**
	 * Check value if match
	 * 
	 * @param $value string
	 * @return string
	 */
	public function check($value) {
		$match = (preg_match('/^[\w+.-]+@[\w+.-]+$/', $value) == 0)? TRUE: FALSE;
		return ($match)? $this->errorMessage: NULL;
	}
}