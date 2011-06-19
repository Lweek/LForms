<?php
function __autoload($class_name) {require_once 'lform_modules/' . strToLower($class_name) . '.php';}
/**
 * PHP class for easy handling HTML forms
 *
 * @author Vladimir Belohradsky <info@lweek.net>
 * @version 0.9.1.29 2011-06-18
 * @package LForms
 */
class LForms implements Countable, ArrayAccess {
	
	const post = 'POST';
	const get = 'GET';
	const version = '0.9.1.29';

	/** @var string $formMethod */
	protected $formMethod = 'POST';
	/** @var string $formAction */
	protected $formAction = '';
	/** @var string $formName */
	protected $formName;
	/** @var array $formItems */
	protected $formItems = NULL;
	/** @var array $formErrors */
	protected $formErrors = NULL;
	/** @var string $formToken */
	protected $formToken = '1';
	/** @var string $emptyMessage */
	protected $emptyMessage = 'Can\'t be empty!';	
	
	/**
	 * @param string $name
	 * @param string $emptyMessage
	 * @return this
	 */
	public function __construct($name, $emptyMessage = NULL) {
	    if ($emptyMessage) $this->emptyMessage = $emptyMessage;
		$this->formName = $name;
		if (session_id() != '') {
			if (isset($_SESSION['form_' . $this->formName])) {
				$this->formToken = $_SESSION['form_' . $this->formName];
			} else {
				$this->formToken = uniqID();
				$_SESSION['form_' . $this->formName] = $this->formToken;
			}
		}
	}

	/**
	 * Set http method
	 * 
	 * @param string $method const GET or POST
	 * @return LForms
	 */
	public function setMethod($method) {
		$this->formMethod = ($method == 'GET')? 'GET': 'POST';
		return $this;
	}

	/**
	 * Set form action path
	 * 
	 * @param string $url
	 * @return LForms
	 */
	public function setAction($url) {
		$this->formAction = $url;
		return $this;
	}

	/**
	 * Add input item into form
	 * 
	 * @param string $type
	 * @param string $name
	 * @param bool $required
	 * @param string $emptyMessage
	 * @return FormItem
	 */
	public function addItem($type, $name, $required = FALSE, $emptyMessage = NULL) {
		try {
			$type = 'FormItem_' . ucfirst($type);
			$this->formItems[$name] = new $type($name, $required);
			if ($emptyMessage) {
				$this->formItems[$name]->setEmptyMessage($emptyMessage);
			} else {
				$this->formItems[$name]->setEmptyMessage($this->emptyMessage);
			}
		}
		catch(Exception $e) {
			throw new Exception('Unknown FormItem type');
		}
		return $this->formItems[$name]; 
	}

	/**
	 * Add group delimiter
	 * 
	 * @param string $name
	 * @param string $title
	 * @return LForm
	 */
	public function addGroup($name, $title) {
		$this->formItems[$name] = array('name' => $name, 'title' => $title);
		return $this;
	}

	/**
	 * Close group delimiter
	 * @return LForm
	 */
	public function closeGroup()
	{
		$this->formItems[uniqID()] = NULL;
		return $this;
	}

	/**
	 * Load values from input to form items
	 * 
	 * @param array $input
	 * @return LForms
	 */
	public function loadValues($input = NULL) {
		if (!$input) $input = ($this->formMethod == 'POST')? $_POST: $_GET;
		foreach ($this->formItems as &$item) {
			if (is_object($item) AND isset($input[$item->getName()])) {
				$item->loadValue($input[$item->getName()]);
			}
		}
		return $this;
	}

	/**
	 * Return form values
	 * 
	 * @return array
	 */
	public function getValues() {
		$values = NULL;
		foreach ($this->formItems as &$item) {
			if (is_object($item) AND ($value = $item->getValue()) !== NULL) {
				if($item->getDependency() && !$this->formItems[$item->getDependency()]->isEmpty()) {
					$values[$item->getName()] = $value;
				} else {
					$values[$item->getName()] = $value;
				}
			}
		}
		return $values;
	}

	/**
	 * Render generic form template
	 * 
	 * @param bool $javascript Render with javascript runtime
	 * @param bool $ajax Form will be send using AJAX
	 * @return string
	 */
	public function render($javascript = FALSE, $ajax = FALSE) {
		$inGroup = FALSE;
		$output = '<form enctype="multipart/form-data" method="' . 
			$this->formMethod . '" action="' . $this->formAction . '" id="lform_' . 
			$this->formName . '"><input type="hidden" name="lform_' . $this->formName . 
			'" value="' . $this->formToken . '" />';
//		if ($javascript) {
//			$jsOutput = '<script type="text/javascript">/* <![CDATA[ */';
//			$jsOutput .= "jQuery(document).ready(function($){ $('#lform_" .
//				$this->formName . "').submit(function(){";
//		}
		if ($this->formItems) foreach ($this->formItems as &$item) {
			if (is_object($item)) {
			    if ($item instanceof FormItem_Hidden) {
			    	$output .= $item->input();
					continue;
				}
				$output .= '<dt>' . $item->label() . '</dt>';
				$output .= '<dd>' . $item->input() . $item->errors() . '</dd>';
				$output .= '<div class="clear"></div>';
				// javascript
//				if ($javascript) {
//					$jsOutput .= $item->javascript('lform_'.$this->formName);
//				}
			} else {
				if ($inGroup) $output .= '</fieldset>';
				if ($item !== NULL) {
					$inGroup = TRUE;
					$output .= '<fieldset id="' . $item['name'] . '"><legend>' . 
						$item['title'] . '</legend>';
				} else {
					$inGroup = FALSE;
				}
			}
		}
		if ($inGroup) $output .= '</fieldset>';
		$output .= '</form>';
//		if ($javascript) {
//			 $output .= $jsOutput. ' return false; });}); /* ]]> */</script>';
//		}
		return $output;
	}

	/**
	 * Validate returned form items
	 * 
	 * @return bool
	 */
	public function isValid() {
		foreach ($this->formItems as &$item) {
			if (is_object($item) AND $errors = $item->validate()) {
				$this->formErrors[$item->getName()] = $errors;
			}
		}
		return ($this->formErrors)? FALSE: TRUE;
	}

	/**
	 * Check if form is already sent
	 * 
	 * @return bool
	 */
	public function isSent() {
		$isSent = FALSE;
		$key = 'lform_' . $this->formName;
		if (isset($_POST[$key]) OR isset($_GET[$key])) {
			$value = (isset($_POST[$key]))? $_POST[$key]: $_GET[$key];
			$isSent = ($value === $this->formToken)? TRUE: FALSE;
		}
		return $isSent;
	}

	/**
	 *	Individual access
	 * 
	 *  @param string $item
	 *	@return	FormItem
	 */
	public function __get($item) {
		return ($this->formItems[$item])? $this->formItems[$item]: NULL;
	}

	/**
	 * Magic method for adding new items nicer way
	 * 
	 * @param string $name
	 * @param array $arguments 
	 * @return NULL|FormItem
	 */
	public function __call($name, $arguments) {
		$item = NULL;
		if (substr($name, 0, 3) == 'add') {
			$itemType = strtolower(substr($name, 3));
			$name = isset($arguments[0])? $arguments[0]: NULL;
			$required = isset($arguments[1])? $arguments[1]: FALSE;
			$emptyMsg = isset($arguments[2])? $arguments[2]: NULL;
			$item = $this->addItem($itemType, $name, $required, $emptyMsg);
		} else {
			throw new Exception('Undefined method or item!');
		}
		return $item;
	}
	
	/**
	 * Countable
	 * 
	 * @return int
	 */
	public function count() {
	    return count($this->formItems);
	}

	/**
	 * Check if item exists
	 * 
	 * @param string $key
	 * @return bool
	 */
    function offsetExists($key) {
		return isset($this->formItems[$key]);
	}

	/**
	 * Unset item
	 * 
	 * @param string $key
	 */
    function offsetUnset($key) {
		unset($this->formItems[$key]);
	}

	/**
	 * Return actual form item value
	 * 
	 * @param string $key
	 * @return mixed
	 */
    function offsetGet($key) {
	    if (!isset($this->formItems[$key])) {
			throw new Exception('Form item of this name doesn\'t exists');
		}
		return $this->formItems[$key]->getValue();
	}
	
	/**
	 * Overwrite of form item is not allowed
	 * 
	 * @param string $key
	 * @param string $value
	 */
    function offsetSet($key, $value) {
	    if (!isset($this->formItems[$key])) {
	    	throw new Exception('Form item of this name doesn\'t exists');
	    }
		$this->formItems[$key]->setValue((string)$value);
	}
}