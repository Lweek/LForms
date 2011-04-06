<?php
/**
 *	Standard text input item for forms
 *
 *	@author	Vladimir Belohradsky <info@lweek.net>
 */
class FormItem_Text extends FormItem
{
	protected $type = 'text';
	protected $maxLength = NULL;

	/**
	 * Sets maximal input length
	 * @return FormItem_Text
	 */
	public function maxLength($length, $message = 'Too long')
	{
		$this->maxLength = (int) $length;
		$this->addValidator('length', $message, (int)$length);
		return $this;
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
		$readonly = ($this->readonly)? ' readonly="readonly"':'';
		$errors = ($this->errors)? ' class="error"':'';
		$length = ($this->maxLength)? ' maxlength="'.$this->maxLength.'"':'';
		return '<input type="'.$this->type.'"' . $length . $errors . $id . $name . $value . $readonly . ' />';
	}
}