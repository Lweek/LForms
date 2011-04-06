<?php
/**
 *	Validate expecting value
 *	@author	Vladimir Belohradsky [info@lweek.net]
 */
class FormValidator_Expecting
{
	protected $errorMessage = NULL; // string
	protected $expectingValue; // string

	/**
	 *  @param $errorMessage string
	 *  @param $parameter mixed
	 *	@return	FormValidator_Length
	 */
	public function __construct($errorMessage = NULL, $parameter = 0)
	{

		$this->errorMessage = $errorMessage;
		$this->expectingValue = $parameter;
		return $this;
	}

	/**
	 *	Check value if match
	 *  @param $value string
	 *	@return	string
	 */
	public function check($value)
	{
		return ($value != $this->expectingValue)? $this->errorMessage: NULL;
	}
}