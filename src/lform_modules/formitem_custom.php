<?php
/**
 * Custom input item for forms
 *
 * @author Vladimir Belohradsky <info@lweek.net>
 * @package LForms
 * @package Lforms\FormItem
 * @version 1.1 2011-06-19
 */
class FormItem_Custom extends FormItem {
	/** @var string $customHtml HTML vystup */
	protected $customHtml = NULL;

	/**
	 * Set custom item code
	 * 
	 * @param string $code
	 * @return FormItem_Custom
	 */
	public function setCustomHtml($code) {
		$this->customHtml = (string) $code;
		return $this;
	}

	/**
	 * Print item
	 * 
	 * @return string
	 */
	public function input() {
		return $this->customHtml;
	}
}