<?php
/**
 *	Standard checkbox input item for forms
 *
 *	@author	Vladimir Belohradsky <info@lweek.net>
 */
final class FormItem_Checkbox extends FormItem
{
	protected $checked = FALSE; // bool

	/**
	 *	Handle value from input
	 *  @param $value bool
	 */	 	
	public function loadValue($value)
	{
		if ($value) $this->checked = TRUE;
	}

	/**
	 *	Get item value
	 *  @return string
	 */	 	
	public function getValue()
	{
		return (string) (!$this->checked)? 
			(($this->defaultValue != NULL)? $this->defaultValue: NULL): $this->value;
	}

	/**
	 *	Print item
	 *  @return string
	 */	 	
	public function input()
	{
		$id = ($this->name)? ' id="' . $this->name . '"':'';
		$name = ($this->name)? ' name="' . $this->name . '"':'';
		$value = ($this->value)? ' value="' . $this->value . '"':'';
		$checked = ($this->checked)? ' checked="checked"':'';
		$errors = ($this->errors)? ' class="error"':'';
		return '<input type="checkbox"' . $errors . $id . $name . $value . $checked . ' />';
	}
}