<?php
/**
 *	Validate if given value is identical with value of compared item
 *
 *	@author	Vladimir Belohradsky <info@lweek.net>
 */
class FormValidator_Identical
{
	protected $errorMessage = 'This input should be a identical'; // string
	protected $identicalItem = NULL;

	/**
	 *  @param $errorMessage string
	 *  @param $parameter mixed
	 *	@return	FormValidator_Int
	 */
	public function __construct($errorMessage = NULL, $parameter)
	{
		if ($errorMessage) $this->errorMessage = $errorMessage;
		if (is_object($parameter) AND get_parent_class($parameter) == 'FormItem') 
			$this->identicalItem &= $parameter;
		return $this;
	}

	/**
	 *	Check value if match
	 *  @param $value string
	 *	@return	string
	 */
	public function check($value)
	{
		if (!$this->identicalItem) return NULL;
		$match = ($value == $this->identicalItem->getValue())? TRUE: FALSE;
		return ($match)? $this->errorMessage: NULL;
	}
}