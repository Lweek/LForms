<?php
/**
 * Validate value as Url adress
 *
 * @author Vladimir Belohradsky <info@lweek.net>
 * @package LForms
 * @package Lforms\FormValidator
 * @version 1.1 2011-06-19
 */
class FormValidator_Url {
	/** @var string $errorMessage */
	protected $errorMessage = 'This should be a valid URL adress';
	
	/**
	 * @param sring $errorMessage
	 * @param mixed $parameter
	 * @return FormValidator_Url
	 */
	public function __construct($errorMessage = NULL, $parameter = NULL) {
		if ($errorMessage) $this->errorMessage = $errorMessage;
		return $this;
	}

	/**
	 * Check value if match
	 *
	 * @return string|NULL
	 */
	public function check($value) {
		$match = (preg_match('#^http(s)?://[\w-_\?\&\;\#]+?$#', $value) == 0)? TRUE: FALSE;
		return ($match)? $this->errorMessage: NULL;
	}
}