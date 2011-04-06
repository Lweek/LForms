<?php
/**
 *	Validate value as Alfanumeric
 *
 *	@author	Vladimir Belohradsky <info@lweek.net>
 */
class FormValidator_Alnum
{
	protected $errorMessage = 'This input should be an alfanumeric text'; // string
	protected $withDiacritis = FALSE; // bool

	/**
	 *  @param $errorMessage string
	 *  @param $parameter mixed
	 *	@return	FormValidator_Alnum
	 */
	public function __construct($errorMessage = NULL, $parameter = NULL)
	{
		if ($errorMessage) $this->errorMessage = $errorMessage;
		if ($parameter) $this->withDiacritics = (bool) $parameter;
		return $this;
	}

	/**
	 *	Check value if match
	 *  @param $value string
	 *	@return	string
	 */
	public function check($value)
	{
		if ($this->withDiacritics)
		{
			$match = (preg_match('#^[\pL\w]+$#u', $value) == 0)? TRUE: FALSE;
		}
		else
		{
			$match = (preg_match('#^[a-zA-Z0-9]+$#', $value) == 0)? TRUE: FALSE;
		}
		return ($match)? $this->errorMessage: NULL;
	}
}