<?php
/**
 * Standard hidden input item for forms
 *
 * @author Vladimir Belohradsky <info@lweek.net>
 * @package LForms
 * @package Lforms\FormItem
 * @version 1.1 2011-06-19
 */
class FormItem_Hidden extends FormItem {
	/**
	 * Print item
	 * 
	 * @return string
	 */
	public function input() {
		$id = ($this->name)? ' id="' . $this->name . '"':'';
		$name = ($this->name)? ' name="' . $this->name . '"':'';
		$value = ($this->value)? ' value="' . $this->value . '"':'';
		return '<input type="hidden"' . $id . $name . $value . ' />';
	}
}