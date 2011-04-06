<?php
/**
 *	Validate char lenght of value
 *
 *	@author	Vladimir Belohradsky <info@lweek.net>
 */
class FormValidator_Length
{
	protected $errorMessage = 'This value isn\'t long enought'; // string
	protected $errorMessageAlt = 'This value lenght isn\'t in range'; // string
	protected $maxLenght; // int
	protected $minLenght = NULL; // int

	/**
	 *  @param $errorMessage string
	 *  @param $parameter mixed
	 *	@return	FormValidator_Length
	 */
	public function __construct($errorMessage = NULL, $parameter = 0)
	{
		if (is_array($parameter))
		{
			$this->minLength = (int) $parameter[0];
			$this->maxLength = (int) $parameter[1];
		}
		else $this->maxLength = (int) $parameter;
		if ($errorMessage) 
		{
			$this->errorMessage = $errorMessage;
			$this->errorMessageAlt = $errorMessage;
		}
		return $this;
	}

	/**
	 *	Check value if match
	 *  @param $value string
	 *	@return	string
	 */
	public function check($value)
	{		
		if ($this->minLenght)
		{
			$length = strlen($value);
			return ($length > $this->maxLength OR $length < $this->minLength)? 
				$this->errorMessageAlt: NULL;
		}
		return (strlen($value) > $this->maxLength)? $this->errorMessage: NULL;
	}
}