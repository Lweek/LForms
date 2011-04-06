<?php
/**
 *	Custom input item for forms
 *
 *	@author	Vladimir Belohradsky <info@lweek.net>
 */
class FormItem_Custom extends FormItem
{
	protected $customHtml = NULL;

	/**
	 *	Set custom item code
	 *  @param $code string
	 *	@return	FormItem_Custom
	 */
	public function setCustomHtml($code)
	{
		$this->customHtml = (string) $code;
		return $this;
	}

	/**
	 *	Print item
	 *	@return	string
	 */
	public function input()
	{
		return $this->customHtml;
	}
}