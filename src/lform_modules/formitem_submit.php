<?php
/**
 * Standard text input item for forms
 *
 * @author Vladimir Belohradsky <info@lweek.net>
 * @package LForms
 * @package Lforms\FormItem
 * @version 1.1 2011-06-19
 */
class FormItem_Submit extends FormItem_Text {
	/** @var string $type */
	protected $type = 'submit';

	/**
	 * No return value
	 * 
	 * @return NULL	 
	 */	 	
	public function getValue() {
		return NULL;
	}
}