<?php
/**
 *	Standard textarea input item for forms
 *
 *	@author	Vladimir Belohradsky <info@lweek.net>
 */
final class FormItem_Textarea extends FormItem_Text
{
	/**
	 *	Print item
     *  @return string
	 */	 	
	public function input()
	{
		$id = ($this->name)? ' id="' . $this->name . '"':'';
		$name = ($this->name)? ' name="' . $this->name . '"':'';
		$readonly = ($this->readonly)? ' readonly="readonly"':'';
		$errors = ($this->errors)? ' class="error"':'';
		return '<textarea' . $errors . $id . $name . $readonly . '>' . $this->value.'</textarea>';
	}
}