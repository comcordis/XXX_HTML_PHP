<?php


class XXX_Component_Link extends XXX_Component_Input
{
	public $CLASS_NAME = 'XXX_Component_Link';
	
	protected $inputType = 'link';
	
	protected $uri = '';			
	protected $label = '';
	protected $icon = '';
	
	protected $preventDefaultAction = false;
				
	public function __construct ($ID = '', $name = '')
	{
		parent::__construct($ID, $name);
	}
	
	public function setPreventDefaultAction ($preventDefaultAction = false)
	{
		$this->preventDefaultAction = $preventDefaultAction ? true : false;
	}
	
	public function preventDefaultAction ()
	{
		$this->preventDefaultAction = true;
	}
	
	public function setURI ($uri = '')
	{
		$this->uri = $uri;
	}
	
	public function setLabel ($label = 'Link')
	{
		if (XXX_Type::isValue($label))
		{
			$this->label = $label;
		}
	}
	
	public function setIcon ($icon)
	{
		$this->icon = $icon;
	}
			
	public function composeHTML ()
	{
		$html = '';
		
		switch ($this->getAttachedFormComposeSide())
		{
			case 'server':
					$tempAttributes = array
					(
						array('key' => 'ID', 'value' => $this->ID . '_nativeLink'),
						array('key' => 'name', 'value' => $this->name),
						array('key' => 'uri', 'value' => $this->uri),
						array('key' => 'readOnly', 'value' => !$this->isEditable()),
						array('key' => 'disabled', 'value' => $this->isDisabled())
					);
					
					$label = '';
					
					if ($this->icon)
					{
						$label .= XXX_HTML_Composer::composeNativeIcon($this->icon);
					}
					
					$label .= $this->label;
					
					$composedNativeLink = XXX_HTML_Composer::composeNativeLink($tempAttributes, $this->uri, $label);
				
				$html .= $composedNativeLink;
				
				$html .= $this->composeFeedbackHTML();
				break;
			case 'client':
				$html .= XXX_HTML_Composer::composeNativeInlineContainer($this->ID . '_container');
				break;
		}
		
		return $html;
	}
	
	public function composeJS ()
	{
		$js = '';
	
		$jsInstanceVariable = $this->getJSInstanceVariable();
		
		$js .= parent::composeInitializationJS();
																	
		$js .= $jsInstanceVariable . '.goToURI(' . XXX_Composer_JS::composeString($this->uri) . ');' . XXX_String::$lineSeparator;
		$js .= $jsInstanceVariable . '.setLabel(' . XXX_Composer_JS::composeString($this->label) . ');' . XXX_String::$lineSeparator;
		
		if ($this->preventDefaultAction)
		{
			$js .= $jsInstanceVariable . '.setPreventDefaultAction(true);' . XXX_String::$lineSeparator;
		}
		
		if (!$this->isEditable())
		{
			$js .= $jsInstanceVariable . '.setEditable(false);' . XXX_String::$lineSeparator;
		}
		if ($this->isDisabled())
		{
			$js .= $jsInstanceVariable . '.setDisabled(true);' . XXX_String::$lineSeparator;
		}
		if (!$this->isValid())
		{
			$js .= $jsInstanceVariable . '.setValid(false);' . XXX_String::$lineSeparator;
		}
		
		$js .= parent::composeJS();
				
		return $js;
	}
}


?>