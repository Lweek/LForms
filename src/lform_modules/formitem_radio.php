<?php
/**
 * Standard select input item for forms
 *
 * @author Vladimir Belohradsky <info@lweek.net>
 * @package LForms
 * @package Lforms\FormItem
 * @version 1.1 2011-06-19
 */
class FormItem_Radio extends FormItem {
	/** @var array $options array(string) */
	protected $options = NULL;
	
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
			$selected = ($this->value == $value)? ' checked="checked"':'';
			$options .= '<input type="radio" value="' . $errors . $value . '"' . 
				$selected . $id . $name . '>' .	$option;
		}
		return '<span class="lform-radiolist">'.$options.'</span>';
	}

	/**
	 * Set list of options
	 *
	 * @param array $list
	 * @return FormItem_Radio
	 */
	public function setList($list) {
		if (is_array($list)) $this->options = $list;
		return $this;
	}
}