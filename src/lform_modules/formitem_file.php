<?php
/**
 * Standard file input item for forms
 *
 * @author Vladimir Belohradsky <info@lweek.net>
 * @package LForms
 * @package Lforms\FormItem
 * @version 1.1 2011-06-19
 */
class FormItem_File extends FormItem {
	/** @var string $requiredFileType Possible filetypes */
	protected $requiredFileType = NULL;
	/** @var string $errorFiletype Message if different filetype */
	protected $errorFiletype = 'Incorrect filetype!';
	/** @var bool $multiple If accept more than one file */
	protected $multiple = FALSE;

	/**
	 * Set filter for file format
	 * 
	 * @param string $requiredFileType MIME type
	 * @return FormItem_File
	 */
	public function setRequiredFiletype(
		$requiredFileType = NULL, $errorFiletype = NULL
	) {
		if ($requiredFileType) $this->requiredFileType = $requiredFileType;
		if ($errorFiletype) $this->errorFiletype = $errorFiletype;
		return $this;
	}

	/**
	 * Return no value or defaultValue if set
	 * 
	 * @return string|NULL
	 */
	public function getValue() {
		$value = ($this->defaultValue && isset($_FILES[$this->name]))? 
			$this->defaultValue: NULL;
		return $value;
	}

	/**
	 * Save uploaded file to final destination
	 * 
	 * @param string $path
	 * @param string $filename
	 * @param bool $fixname
	 * @return bool
	 */
    public function save(
		$path = '/', $filename = NULL, $fixName = FALSE, $numbering = TRUE
	) {
		$result = NULL;
		// if multiple files are awaited
		if ($this->multiple) {
			$fileNumber = 0;
			$names = $this->getParameter('name');
			foreach($names as $name) {
				$target = ($filename)? $filename: $name;
				$target = strtolower($target);
				$target = preg_filter('/[^\w-]/i', $target);
				if ($numbering) $target = ($fileNumber++) . '_' . $target;
				move_uploaded_file($_FILES[$name]['tmp_name'], $path . $target);
			}
			$result = TRUE;
		// single file handler
		} else {
			// if file name missing
			if (!$filename) $filename = $this->getParameter('name');
			// make filename safe
			if ($fixName) {
				$filename = strtolower($filename);
				$filename = preg_filter('/[^\w-]/i', $filename);
			}
			// move file to its final destination
			if ($this->getValue() && file_exists($path)) { 
				$result = (
					move_uploaded_file(
						$_FILES[$this->name]['tmp_name'], 
						$path . $filename
					)
				)? $filename: FALSE;
			}
		}
		return $result;
		// FIXME -- tuhle metodu bude lepsi zkontrolovat jestli dela co ma
    }

	/**
	 * Get info about file
	 * 
	 * @param string $info
	 * @return string
	 */
    public function getParameter($info = 'size') {
		$value = NULL;
        if (isset($_FILES[$this->name][$info])) {
			$value = $_FILES[$this->name][$info];
		} else {
			throw new Exception('Unknown parameter!');
		}
		return $value;
    }

	/**
	 * Tests if uploaded file is required type
	 * 
	 * @param string $type
     * @return bool
	 */
	public function isFiletype($type = NULL) {
		$valid = TRUE;
		$mime = $this->getParameter('type');
		if (is_array($mime)) foreach($mime as &$fileMime) {
			if (!preg_match('/('.$type.')/i', $mime)) $valid = FALSE;
		} else {
			if (!preg_match('/('.$type.')/i', $mime)) $valid = FALSE;
		}
		return $valid;
	}

    /**
     * Tests if uploaded file is an Image
	 * 
     * @param string $type
     * @return bool     
     */
    public function isImage($type = NULL) {
        switch ($type) {
        	case 'jpg': return $this->isFiletype('jpg|jpeg');
        	case 'gif': return $this->isFiletype('gif');
        	case 'png': return $this->isFiletype('png');
			default: return (!$type)? 
				$this->isFiletype('image'): $this->isFiletype($type);
		}
    }

	/**
	 * Check if form item value is empty
	 * 
	 * @return bool
	 */
	public function isEmpty() {
		return ($this->getValue())? FALSE: TRUE;
	}

	/**
	 * Check all validators
	 * 
	 * @return array
	 */
	public function validate() {
		if (!$this->defaultValue) {
			$this->defaultValue = $this->getParameter('name');
		}
		if ($this->multiple) {
			$this->defaultValue = implode(',', $this->getParameter('name'));
		}
		parent::validate();
		if (
			$this->required 
			&& $this->requiredFileType 
			&& !$this->isFiletype($this->requiredFileType)
		) {
			$this->errors[] = $this->errorFiletype;
		}
		return $this->errors;
	}

	/**
	 * Print item
	 * 
	 * @return string
	 */
	public function input() {
		$id = ($this->name)? ' id="' . $this->name . '"':'';
		$name = ($this->name)? ' name="' . $this->name . 
				(($this->multiple)?'[]':'') . '"':'';
		$errors = ($this->errors)? ' class="error"':'';
		$multiple = ($this->multiple)? ' multiple="multiple"':'';
		return '<input type="file"' . $errors . $id . $name . $multiple . ' />';
	}

	/**
	 * Set if multiple files can be uploaded
	 * 
	 * @param bool $multiple 
	 * @return FormItem_File
	 */
	public function setMultiple($multiple = FALSE) {
		$this->multiple = ($multiple)? TRUE: FALSE;
		return $this;
	}
	
	/**
	 * Return TRUE if multiple
	 * 
	 * @return bool
	 */
	public function isMultiple() {
		return $this->multiple;
	}
}