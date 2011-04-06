<?php
require_once 'recaptchalib.php';
class FormValidator_Recaptcha
{    
    protected $privateKey = '';
    protected $errorMessage = 'You should pass thru recaptcha'; // string

	/**
	 *	@return	FormValidator_Int
	 */
	public function __construct($errorMessage = NULL, $parameter = NULL)
	{
		if ($errorMessage) $this->errorMessage = $errorMessage;
	}

	/**
	 *	Check value if match
	 *	@return	string
	 */
	public function check(&$value)
	{
		$resp = recaptcha_check_answer (
			$this->privateKey,
            $_SERVER["REMOTE_ADDR"],
            $_POST["recaptcha_challenge_field"],
            $_POST["recaptcha_response_field"]
		);
		$value = '';
		return (!$resp->is_valid)? $this->errorMessage: NULL;
	}
}

final class FormItem_Recaptcha extends FormItem_Text
{
	protected $publicKey = '';
	protected $validators = NULL; // validator
	protected $value = 'musttest';
	protected $require = FALSE;
	
	public function __construct($name = NULL, $required = FALSE)
	{
		$this->validators[] = new FormValidator_Recaptcha($required);
		return $this;
	}

	public function addValidator()
	{
		return $this;
	}
	/**
	 *	Print item
	 */	 	
	public function input()
	{
		return recaptcha_get_html($this->publicKey);
	}

	public function getValue()
	{
		return NULL;
	}
}