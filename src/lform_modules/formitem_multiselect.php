<?php
/**
 * Standard select input item for forms
 *
 * @author Vladimir Belohradsky <info@lweek.net>
 * @package LForms
 * @package Lforms\FormItem
 * @version 1.1 2011-06-19
 */
class FormItem_Multiselect extends FormItem_Select {
	/**
	 * Print item
	 * 
	 * @return string
	 */
	public function input() {
		$id = ($this->name)? ' id="' . $this->name . '"':'';
		$name = ($this->name)? ' name="' . $this->name . '"':'';
		$options = '';
		$errors = ($this->errors)? ' class="error"':'';
		if ($this->options) foreach ($this->options as $value => $option) {
			$selected = ($this->value == $value)? ' selected="selected"':'';
			$options .= '<option value="' . $value . '"' . $selected . '>' .
				$option . '</option>';
		}
		return '<select multiple="multiple" ' . $errors . $id . $name . '>' . $options . '</select>';
	}
}