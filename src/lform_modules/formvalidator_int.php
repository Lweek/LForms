<?php
/**
 *	Validate value as INT
 *
 *	@author	Vladimir Belohradsky <info@lweek.net>
 */
class FormValidator_Int
{
	protected $errorMessage = 'This input should be a number'; // string

	/**
	 *  @param $errorMessage string
	 *  @param $parameter mixed
	 *	@return	FormValidator_Int
	 */
	public function __construct($errorMessage = NULL, $parameter = NULL)
	{
		if ($errorMessage) $this->errorMessage = $errorMessage;
		return $this;
	}

	/**
	 *	Check value if match
	 *  @param $value string
	 *	@return	string
	 */
	public function check($value)
	{
		$match = (preg_match('/^[0-9]+$/', $value) == 0)? TRUE: FALSE;
		return ($match)? $this->errorMessage: NULL;
	}
}