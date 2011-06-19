<?php
/**
 * Validate if given value is identical with value of compared item
 *
 * @author Vladimir Belohradsky <info@lweek.net>
 * @package LForms
 * @package Lforms\FormValidator
 * @version 1.1 2011-06-19
 */
class FormValidator_Identical {
	/** @var string $errorMessage */
	protected $errorMessage = 'This input should be a identical';
	/** @var FormItem $identicalItem */
	protected $identicalItem = NULL;

	/**
	 * @param string $errorMessage
	 * @param mixed $parameter
	 * @return	FormValidator_Int
	 */
	public function __construct($errorMessage = NULL, $parameter) {
		if ($errorMessage) $this->errorMessage = $errorMessage;
		if (is_object($parameter) && get_parent_class($parameter) == 'FormItem') 
			$this->identicalItem &= $parameter;
		return $this;
	}

	/**
	 * Check value if match
	 *
	 * @param $value string
	 * @return string
	 */
	public function check($value) {
		if (!$this->identicalItem) return NULL;
		$match = ($value == $this->identicalItem->getValue())? TRUE: FALSE;
		return ($match)? $this->errorMessage: NULL;
	}
}