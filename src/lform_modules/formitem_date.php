<?php
/**
 *	Date input item using selectboxes for forms
 *
 *	@author	Vladimir Belohradsky <info@lweek.net>
 */
class FormItem_Date extends FormItem
{
	protected $year = NULL; //int
	protected $month = NULL; //int
	protected $day = NULL; //int
	protected $emptyValues = array('----------');

	/**
	 *	Return item value	
	 *	@return	string
	 */
	public function getValue()
	{
		return (string) ($this->isEmpty())? 
			(($this->defaultValue)? $this->defaultValue: NULL): 
				$this->year . '-' . $this->month . '-' . $this->day;
	}

	/**
	 *	Handle value from input
	 *  @param $value string with correct DATETIME
	 *	@return	FormItem
	 */
	public function loadValue($value)
	{
		$value = $value[0] . '-' . $value[1] . '-' . $value[2];
		foreach($this->emptyValues as &$emptyValue)
		{
			if ($emptyValue == $value) return $this;
		}
		$this->setValue($value);
		return $this;
	}

	/**
	 *	Set item value
	 *  @param $value string with correct DATETIME
	 *	@return	FormItem
	 */
	public function setValue($value)
	{
		if (date('Y-m-d', strtotime($value)) != $value) return $this;
		$value = explode('-', $value);
		$this->year = $value[0];
		$this->month = $value[1];
		$this->day = $value[2];
		return $this;
	}

	/**
	 *	Print item
	 *	@return	string
	 */
	public function input()
	{
		$year = (int) date('Y', time());
		$year = $this->buildSelect($year-120, $year+10, 'year');
		$month = $this->buildSelect(1, 12, 'month');
		$day = $this->buildSelect(1, 31, 'day');
		return $year . $month . $day;
	}

	/**
	 *	Build a select
	 *  @param $from int
	 *  @param $to int
	 *  @param $name string
	 *	@return	string
	 */
	protected function buildSelect($from, $to, $name)
	{
		if ($name == 'year')
		{
			$format = '%04s';
			$options = '';
		}
		else
		{
			$format = '%02s';
			$options = '<option>--</option>';
		}
		for ($i = $from; $i <= $to; $i++)
		{
			$selected = ($i == $this->$name)? ' selected="selected"': '';
			if ($name == 'year') $options = '<option value="' . 
				sprintf($format, $i) . '"' . $selected . '>' . 
				sprintf($format, $i) . '</option>' . $options;
			else $options .= '<option value="' . sprintf($format, $i) . '"' . 
				$selected . '>' . sprintf($format, $i) . '</option>';
		}
		if ($name == 'year') $options = '<option>----</option>' . $options;
		$id = ($this->name)? ' id="' . $name . '_' . $this->name . '"':'';
		$name = ($this->name)? ' name="' . $this->name . '[]"':'';
		$errors = ($this->errors)? ' class="error"':'';
		return '<select' . $errors . $id . $name . '>' . $options . '</select>';
	}
}