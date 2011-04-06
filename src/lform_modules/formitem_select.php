<?php
/**
 *	Standard select input item for forms
 *
 *	@author	Vladimir Belohradsky <info@lweek.net>
 */
class FormItem_Select extends FormItem
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
			if (is_array($option)) 
			{
				$options .= '<optgroup label="'.$value.'">';
				foreach ($option as $sValue => $sOption)
				{
				$selected = ($this->value == $sValue)?' selected="selected"':'';
				$options .= '<option value="' . $sValue . '"' . $selected . '>'.
					$sOption . '</option>';
				}
				$options .= '</optgroup>';
			}
			else
			{
				$selected = ($this->value == $value)? ' selected="selected"':'';
				$options .= '<option value="' . $value . '"' . $selected . '>' .
					$option . '</option>';
			}
		}
		return '<select ' . $errors . $id . $name . '>'.$options.'</select>';
	}

	/**
	 *	Set list of options
	 *  @param $list array
	 *	@return	FormItem
	 */
	public function setList($list)
	{
		if (is_array($list)) $this->options = $list;
		return $this;
	}
}