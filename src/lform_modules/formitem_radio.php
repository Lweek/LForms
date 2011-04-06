<?php
/**
 *	Standard select input item for forms
 *
 *	@author	Vladimir Belohradsky <info@lweek.net>
 */
class FormItem_Radio extends FormItem
{
	protected $options = NULL; // array of string
	
	/**
	 *	Print item
	 *	@return	string	 
	 */
	public function input()
	{
		$id = ($this->name)? ' id="' . $this->name . '"':'';
		$name = ($this->name)? ' name="' . $this->name . '"':'';
		$options = '';
		$errors = ($this->errors)? ' class="error"':'';
		if ($this->options) foreach ($this->options as $value => $option)
		{
			$selected = ($this->value == $value)? ' checked="checked"':'';
			$options .= '<input type="radio" value="' . $errors . $value . '"' . 
				$selected . $id . $name . '>' .	$option;
		}
		return '<span class="lform-radiolist">'.$options.'</span>';
	}

	/**
	 *	Set list of options
	 *  @param $list array
	 *	@return	FormItem_Radio
	 */
	public function setList($list)
	{
		if (is_array($list)) $this->options = $list;
		return $this;
	}
}