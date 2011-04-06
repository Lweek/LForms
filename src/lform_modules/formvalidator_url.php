<?php
/**
 *	Validate value as Url adress
 *	@author	Vladimir Belohradsky [info@lweek.net]
 */
class FormValidator_Url
{
	protected $errorMessage = 'This should be a valid URL adress'; // string
	
	/**
	 *	@return	FormValidator_Int
	 */
	public function __construct($errorMessage = NULL, $parameter = NULL)
	{
		if ($errorMessage) $this->errorMessage = $errorMessage;
		return $this;
	}

	/**
	 *	Check value if match
	 *	@return	string
	 */
	public function check($value)
	{
		$match = (preg_match('#^http(s)?://[\w-_\?\&\;\#]+?$#', $value) == 0)? TRUE: FALSE;
		return ($match)? $this->errorMessage: NULL;
	}
}