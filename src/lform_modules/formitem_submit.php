<?php
/**
 *	Standard text input item for forms
 *
 *	@author	Vladimir Belohradsky <info@lweek.net>
 */
final class FormItem_Submit extends FormItem_Text
{
	protected $type = 'submit';

	/**
	 *	No return value
	 *	@return	NULL	 
	 */	 	
	public function getValue()
	{
		return NULL;
	}
}