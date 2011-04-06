<?php
/**
 *	Abstract Class representing form item
 *
 *	@author Vladimir Belohradsky <info@lweek.net>
 */
abstract class FormItem
{
	protected $name; // string
	protected $value = NULL; // string
	protected $defaultValue = NULL; // string
	protected $label; // string
	protected $description = NULL; // string
	protected $validators = NULL; // validator
	protected $errors = NULL; // array of string
	protected $required = FALSE; // bool
	protected $emptyValues = array(''); // array
	protected $dependent = NULL; // string
	protected $readonly = FALSE; // bool
	protected $messageEmpty = 'Can\'t be empty!'; // string

	/**
	 *  @param $name string
	 *  @param $required bool
	 */
	public function __construct($name, $required = FALSE)
	{
		$this->name = (string) $name;
		$this->required = ($required)? TRUE: FALSE;
	}

	/**
	 *  Set if formitem is readonly
	 *  @param $status Boolean
	 */
	public function readonly($status = TRUE)
	{
	    $this->readonly = ($status === TRUE)? TRUE: FALSE;
	}

	/**
	 *	Return item name
	 *	@return	string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 *	Set dependency
	 *  @param $name string
	 *	@return	FormItem
	 */
	public function setDependency($name)
	{
		$this->dependent = $name;
		return $this;
	}

	/**
	 *	Return name of item which this item is dependent to	
	 *	@return string
	 */
	public function getDependency()
	{
		return $this->dependent;
	}

	/**
	 *	Handle value from input
	 *  @param $value string
	 *	@return	FormItem
	 */
	public function loadValue($value)
	{
		foreach($this->emptyValues as &$emptyValue)
		{
			if ($emptyValue == $value) return $this;
		}
		$this->value = $value;
		return $this;
	}

	/**
	 *	Set item default value if post empty
	 *  @param $value string
	 *	@return	FormItem
	 */
	public function setDefaultValue($value)
	{
		$this->defaultValue = (string) $value;
		return $this;
	}

	/**
	 *	Set item value
	 *  @param $value string
	 *	@return	FormItem
	 */
	public function setValue($value)
	{
		$this->value = $value;
		return $this;
	}
	
	/**
	 *	Get item value
	 *	@return	mixed
	 */
	public function getValue()
	{ 
		return (string) ($this->isEmpty())? 
			(($this->defaultValue)? $this->defaultValue: NULL): $this->value;
	}

	/**
	 *	Check if form item value is empty
	 *	@return	bool
	 */
	public function isEmpty()
	{
		foreach($this->emptyValues as &$emptyValue)
		{
			if ($emptyValue == $this->value) return TRUE;
		}
		return FALSE;
	}	 	

	/**
	 *	Set item label and eventualy item description
	 *  @param $label string
	 *  @param $description string
	 *	@return	FormItem
	 */
	public function setLabel($label, $description = NULL)
	{
		$this->label = $label;
		$this->description = $description;
		return $this;
	}

	/**
	 *	Print label for item
	 *	@return	string
	 */
	public function label()
	{
		$description = ($this->description)?
			'<span>' . $this->description . '</span>': '';
		return '<label for="' . $this->name . '">' . $this->label .
			$description . '</label>';
	}
	
	/**
	 *	Print errors
	 *	@return string
	 */
	public function errors()
	{
		if (!$this->errors) return '';
		
		$errors = NULL;
		foreach ($this->errors as $error)
		{
			$errors .= '<span>' . $error . '</span>';
		}
		return '<div class="lform-item-errors">' . $errors . '</div>';
	}

	/**
	 *	Add Values which is going to be interpreted as NULL
	 *  @param $value string
	 *	@return	FormItem
	 */
	public function addEmptyValue($value)
	{
		if (is_array($value))
		{ 
			$this->emptyValues = array_merge($this->emptyValues, $value);
		}
		else $this->emptyValues[] = $value;
		return $this;
	}

	/**
	 *	Add Validator
	 *  @param $validatorName string
	 *  @param $errorMessage string
	 *	@return	FormItem
	 */
	public function addValidator($validatorName, $errorMessage = NULL, 
		$parameter = NULL)
	{
		$validatorName = 'FormValidator_' . ucfirst($validatorName);
		$this->validators[] = new $validatorName($errorMessage, $parameter);
		return $this;
	}

	/**
	 *	Check all validators
	 *	@return	array	 
	 */
	public function validate()
	{
		// if not required but empty then valid
		if (!$this->required && $this->isEmpty()) return NULL;
		// if required but empty then invalid
		if ($this->required && $this->isEmpty()) 
		{
			$this->errors[] = $this->emptyMessage;
		}
		// if required and not empty then check validators
		else
		{
			if (!$this->validators || $this->isEmpty()) return NULL;
			foreach ($this->validators as &$validator)
			{
				$error = $validator->check($this->value);
				if ($error) $this->errors[] = $error;
			}
		}
		return $this->errors;
	}

	/**
	 * Override required parameter
	 * @param type $required
	 * @return FormItem
	 */
	public function required($required = TRUE)
	{
		$this->required = ($required)? TRUE: FALSE;
		return $this;
	}

	/**
	 *  Override "can't be empty" message
	 *  @param $message string
	 */
	public function setEmptyMessage($message)
	{
	    $this->emptyMessage = $message;
	}
}