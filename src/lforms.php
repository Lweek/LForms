<?php
function __autoload($class_name) {
	require_once 'lform_modules/' . strToLower($class_name) . '.php';
}
/**
 * PHP class for easy handling Forms
 *
 * @author Vladimir Belohradsky <info@lweek.net>
 * @version 0.9.1.29
 */
class LForms implements Countable, ArrayAccess
{
	const post = 'POST';
	const get = 'GET';
	const version = '0.9.1.29';

	protected $formMethod = 'POST'; // string
	protected $formAction = ''; // string
	protected $formName; // string
	protected $formItems = NULL; // array of FormItem*
	protected $formErrors = NULL; // array of string
	protected $formToken = '1'; // string
	protected $emptyMessage = 'Can\'t be empty!';	
	
	/**
	 * @param $name string
	 * @param $emptyMessage string
	 */
	public function __construct($name, $emptyMessage = NULL)
	{
	    if ($emptyMessage) $this->emptyMessage = $emptyMessage;
		$this->formName = $name;
		if (session_id() != '') 
		{
			if (isset($_SESSION['form_' . $this->formName]))
			{
				$this->formToken = $_SESSION['form_' . $this->formName];
			}
			else
			{
				$this->formToken = uniqID();
				$_SESSION['form_' . $this->formName] = $this->formToken;
			}
		}
	}

	/**
	 * Set http method
	 * @param $method const GET or POST
	 * @return LForms
	 */
	public function setMethod($method)
	{
		$this->formMethod = ($method == 'POST')? 'POST': 'GET';
		return $this;
	}

	/**
	 * Set form action path
	 * @param $url string
	 * @return LForms
	 */
	public function setAction($url)
	{
		$this->formAction = $url;
		return $this;
	}

	/**
	 * Add input item into form
	 * @param $type string
	 * @param $name string
	 * @param $required bool
	 * @param $emptyMessage string
	 * @return FormItem
	 */
	public function addItem($type, $name, $required = FALSE,
		$emptyMessage = NULL)
	{
		$type = 'FormItem_' . ucfirst($type);
		$this->formItems[$name] = new $type($name, $required);
		if ($emptyMessage)
		{
			$this->formItems[$name]->setEmptyMessage($emptyMessage);
  		}
  		else
  		{
			$this->formItems[$name]->setEmptyMessage($this->emptyMessage);
		}
		return $this->formItems[$name]; 
	}

	/**
	 * Add group delimiter
	 * @param $name string
	 * @param $title string
	 * @return LForm
	 */
	public function addGroup($name, $title)
	{
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
	 * @param $input array
	 * @return LForms
	 */
	public function loadValues($input = NULL)
	{
		if (!$input) $input = ($this->formMethod == 'POST')? $_POST: $_GET;
		foreach ($this->formItems as &$item)
		{
			if (is_object($item) AND isset($input[$item->getName()])) 
			{
				$item->loadValue($input[$item->getName()]);
			}
		}
		return $this;
	}

	/**
	 * Return form values
	 * @return array
	 */
	public function getValues()
	{
		$values = NULL;
		foreach ($this->formItems as &$item)
		{
			if (is_object($item) AND ($value = $item->getValue()) !== NULL)
			{
				if($item->getDependency())
				{ 
					if(!$this->formItems[$item->getDependency()]->isEmpty())
						$values[$item->getName()] = $value;
				}
				else $values[$item->getName()] = $value;
			}
		}
		return $values;
	}

	/**
	 * Render generic form template
	 * @return string
	 */
	public function render()
	{
		$inGroup = FALSE;
		$output = '<form enctype="multipart/form-data" method="' . 
			$this->formMethod . '" action="' . $this->formAction . '" id="lform_' . 
			$this->formName . '"><input type="hidden" name="lform_' . $this->formName . 
			'" value="' . $this->formToken . '" />';
		if ($this->formItems) foreach ($this->formItems as &$item)
		{
			if (is_object($item))
			{
			    if ($item instanceof FormItem_Hidden)
				{
			    	$output .= $item->input();
					continue;
				}
				$output .= '<dt>' . $item->label() . '</dt>';
				$output .= '<dd>' . $item->input() . $item->errors() . '</dd>';
				$output .= '<div class="clear"></div>';
			}
			else
			{
				if ($inGroup) $output .= '</fieldset>';
				if ($item !== NULL)
				{
					$inGroup = TRUE;
					$output .= '<fieldset id="' . $item['name'] . '"><legend>' . 
						$item['title'] . '</legend>';
				}
				else
				{
					$inGroup = FALSE;
				}
			}
		}
		if ($inGroup) $output .= '</fieldset>';
		$output .= '</form>';
		return $output;
	}

	/**
	 * Validate returned form items
	 * @return bool
	 */
	public function isValid()
	{
		foreach ($this->formItems as &$item)
		{
			if (is_object($item) AND $errors = $item->validate())
			{
				$this->formErrors[$item->getName()] = $errors;
			}
		}
		return ($this->formErrors)? FALSE: TRUE;
	}

	/**
	 * Check if form is already sent
	 * @return bool
	 */
	public function isSent()
	{
		$key = 'lform_' . $this->formName;
		if (isset($_POST[$key]) OR isset($_GET[$key]))
		{
			$value = (isset($_POST[$key]))? $_POST[$key]: $_GET[$key];
			return ($value === $this->formToken)? TRUE: FALSE;
		}
		return FALSE;
	}

	/**
	 *	Individual access
	 *  @param $item string
	 *	@return	FormItem
	 */
	public function __get($item)
	{
		return ($this->formItems[$item])? $this->formItems[$item]: NULL;
	}
	
	/**
	 * Countable
	 * @return int
	 */
	public function count()
	{
	    return count($this->formItems);
	}

	/**
	 * Check if item exists
	 * @param $key string
	 * @return bool
	 */
    function offsetExists($key)
	{
		return isset($this->formItems[$key]);
	}

	/**
	 * Unset item
	 * @param $key string
	 */
    function offsetUnset($key)
	{
		unset($this->formItems[$key]);
	}

	/**
	 * Return actual form item value
	 * @param $key string
	 * @return mixed
	 */
    function offsetGet($key)
	{
	    if (isset($this->formItems[$key]))
	    {
			return $this->formItems[$key]->getValue();
		}
		else
		{
	    	throw new Exception('Form item of this name doesn\'t exists');
	    }
	}
	
	/**
	 * Overwrite of form item is not allowed
	 * @param $key string
	 * @param $value string
	 */
    function offsetSet($key, $value)
	{
	    if (isset($this->formItems[$key]))
	    {
			$this->formItems[$key]->setValue((string)$value);
		}
		else
		{
	    	throw new Exception('Form item of this name doesn\'t exists');
	    }
	}
}