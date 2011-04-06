<?php
/**
 *	Standard file input item for forms
 *
 *	@author	Vladimir Belohradsky <info@lweek.net>
 */
class FormItem_File extends FormItem
{
	protected $requiredFileType = NULL;
	protected $errorFiletype = 'Incorrect filetype!';
	protected $multiple = FALSE;

	/**
	 *  Set filter for file format
	 *  @param $requiredFileType string mime
	 */
	public function setRequiredFiletype($requiredFileType = NULL, 
		$errorFiletype = NULL)
	{
		if ($requiredFileType) $this->requiredFileType = $requiredFileType;
		if ($errorFiletype) $this->errorFiletype = $errorFiletype;
	}

	/**
	 *	Return no value or defaultValue if set
	 *  @return string
	 */
	public function getValue() 
	{
		return (string) ($this->defaultValue AND isset($_FILES[$this->name]))? 
			$this->defaultValue: NULL;
	}

	/**
	 *	Save uploaded file to final destination
	 *  @param $path string
	 *  @param $filename string
	 *  @param $fixname bool
	 *	@return bool
	 */
    public function save($path = '/', $filename = NULL, $fixName = FALSE, 
			$numbering = TRUE) 
    {
		if ($this->multiple)
		{
			$i = 0;
			$names = $this->getParameter('name');
			foreach($names as $name)
			{
				$target = ($filename)? $filename: $name;
				$target = preg_filter('/[^\w-]/i', strtolower($target));
				if ($numbering) $target = ($i++) . '_' . $target;
				move_uploaded_file($_FILES[$name]['tmp_name'], $path . $target);
			}
			return TRUE;
		}
		else
		{
			if (!$filename) $filename = $this->getParameter('name');
			if ($fixName) $filename = preg_filter('/[^\w-]/i', 
				strtolower($filename)
			);
			if ($this->getValue() AND file_exists($path))
			{ 
				return (move_uploaded_file(
					$_FILES[$this->name]['tmp_name'], 
					$path . $filename
				))? $filename: FALSE;
			}
			else return NULL;
		}
    }

	/**
	 *	Get info about file
	 *  @param $info string
	 *	@return	string
	 */
    public function getParameter($info = 'size')
    {
        return (isset($_FILES[$this->name][$info]))? 
			$_FILES[$this->name][$info]: NULL;
    }

	/**
	 *	Tests if uploaded file is required type
	 *  @param $type string
     *	@return	bool
	 */
	public function isFiletype($type = NULL)
	{
		$mime = $this->getParameter('type');
		if (is_array($mime)) foreach($mime as &$fileMime)
		{
			if (!preg_match('/('.$type.')/i', $mime)) return FALSE;
		}
		else return (preg_match('/('.$type.')/i', $mime))? TRUE: FALSE;
		return TRUE;
	}

    /**
     *	Tests if uploaded file is an Image
     *  @param $type string
     *	@return	bool     
     */
    public function isImage($type = NULL)
    {
        switch ($type)
        {
        	case 'jpg': return $this->isFiletype('jpg|jpeg')? TRUE: FALSE;
        	case 'gif': return $this->isFiletype('gif')? TRUE: FALSE;
        	case 'png': return $this->isFiletype('png')? TRUE: FALSE;
			default: return (!$type)? 
				($this->isFiletype('image')? TRUE: FALSE):
				($this->isFiletype($type))? TRUE: FALSE;
		}
    }

	/**
	 *	Check if form item value is empty
	 *	@return	bool
	 */
	public function isEmpty()
	{
		return ($this->getValue())? FALSE: TRUE;
	}

	/**
	 *	Check all validators
	 *	@return	array
	 */
	public function validate()
	{
		if (!$this->defaultValue) 
			$this->defaultValue = $this->getParameter('name');
		if ($this->multiple)
			$this->defaultValue = implode(',', $this->getParameter('name'));
		parent::validate();
		if ($this->required AND $this->requiredFileType 
			AND !$this->isFiletype($this->requiredFileType)
		) 
		{
			$this->errors[] = $this->errorFiletype;
		}
		return $this->errors;
	}

	/**
	 *	Print item
	 *	@return	string
	 */
	public function input()
	{
		$id = ($this->name)? ' id="' . $this->name . '"':'';
		$name = ($this->name)? ' name="' . $this->name . 
				(($this->multiple)?'[]':'') . '"':'';
		$errors = ($this->errors)? ' class="error"':'';
		$multiple = ($this->multiple)? ' multiple="multiple"':'';
		return '<input type="file"' . $errors . $id . $name . $multiple . ' />';
	}

	/**
	 * Set if multiple files can be uploaded
	 * @param type $multiple 
	 * @return FormItem
	 */
	public function setMultiple($multiple = FALSE)
	{
		$this->multiple = ($multiple)? TRUE: FALSE;
		return $this;
	}
	
	/**
	 * Return TRUE if multiple
	 * @return bool
	 */
	public function isMultiple()
	{
		return $this->multiple;
	}
}