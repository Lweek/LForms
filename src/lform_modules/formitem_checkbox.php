<?php
/**
 * Standard checkbox input item for forms
 *
 * @author Vladimir Belohradsky <info@lweek.net>
 * @package LForms
 * @package Lforms\FormItem
 * @version 1.1 2011-06-19
 */
class FormItem_Checkbox extends FormItem {
	/** @var bool $checked If checbox is checked */
	protected $checked = FALSE;

	/**
	 * Handle value from input
	 * 
	 * @param bool $value
	 * @return void
	 */	 	
	public function loadValue($value) {
		if ($value) $this->checked = TRUE;
	}

	/**
	 * Get item value
	 * 
	 * @return string
	 */	 	
	public function getValue() {
		$value = '';
		if (!$this->checked) {
			$value = ($this->defaultValue != NULL)? $this->defaultValue: '';
		} else {
			$value = $this->value;
		}
		return (string) $value;
	}

	/**
	 * Print item
	 * 
	 * @return string
	 */	 	
	public function input() {
		$id = ($this->name)? ' id="' . $this->name . '"':'';
		$name = ($this->name)? ' name="' . $this->name . '"':'';
		$value = ($this->value)? ' value="' . $this->value . '"':'';
		$checked = ($this->checked)? ' checked="checked"':'';
		$errors = ($this->errors)? ' class="error"':'';
		return '<input type="checkbox"' . $errors . $id . $name . $value . $checked . ' />';
	}
}