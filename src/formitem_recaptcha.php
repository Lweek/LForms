<?php
require_once 'recaptchalib.php';
/**
 * This is recaptcha FormItem validator for LForms
 * 
 * @author Vladimir Belohradsky <info@lweek.net>
 * @package LForms
 * @subpackage LForms\FormValidator
 * @version 1.1 2011-06-19
 */
class FormValidator_Recaptcha {
	/** @var string $privateKey */
    protected $privateKey = '';
	/** @var string $errorMessage */
    protected $errorMessage = 'You should pass thru recaptcha';

	/**
	 * @param string $errorMessage
	 * @param mixed $parameter 
	 * @return FormValidator_Int
	 */
	public function __construct($errorMessage = NULL, $parameter = NULL) {
		if ($errorMessage) $this->errorMessage = $errorMessage;
	}

	/**
	 * Check value if match
	 * 
	 * @param string $value
	 * @return string
	 */
	public function check(&$value) {
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

/**
 * This is recaptcha FormItem for LForms
 * 
 * @author Vladimir Belohradsky <info@lweek.net>
 * @package LForms
 * @subpackage LForms\FormItem
 * @version 1.1 2011-06-19
 * @final
 */
final class FormItem_Recaptcha extends FormItem_Text {
	/** @var string $publicKey */
	protected $publicKey = '';
	/** @var arrray $validators */
	protected $validators = NULL;
	/** @var string $value */
	protected $value = 'musttest';
	/** @var bool $required */
	protected $required = FALSE;
	
	/**
	 * @param string $name
	 * @param bool $required
	 * @return FormItem_Recaptcha 
	 */
	public function __construct($name = NULL, $required = FALSE) {
		$this->validators[] = new FormValidator_Recaptcha($required);
		return $this;
	}

	/**
	 * This item cannot have validator
	 * 
	 * @return FormItem_Recaptcha 
	 */
	public function addValidator() {
		return $this;
	}

	/**
	 * Print item
	 */	 	
	public function input() {
		return recaptcha_get_html($this->publicKey);
	}

	/**
	 * Override return
	 * 
	 * @return NULL
	 */
	public function getValue() {
		return NULL;
	}
}