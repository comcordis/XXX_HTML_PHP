<?php


class XXX_Component_Button extends XXX_Component_Input
{
	public $CLASS_NAME = 'XXX_Component_Button';
	
	protected $inputType = 'button';
	
	protected $buttonAction = 'custom';			
	protected $label = 'Button';
	
	protected $preventDefaultAction = false;
		
	public function __construct ($ID = '', $name = '')
	{
		parent::__construct($ID, $name);
		
		$this->setPresentation(array(
			'buttonEmotion' => 'normal'
		));
	}
	
	public function setPresentation (array $presentation = array())
	{
		parent::setPresentation($presentation);	
		
		$this->presentation['buttonEmotion'] = XXX_Default::toOption($this->presentation['buttonEmotion'], array('normal', 'safe', 'premium', 'explicit'), 'normal');		
	}
		
	public function setPreventDefaultAction ($preventDefaultAction = false)
	{
		$this->preventDefaultAction = $preventDefaultAction ? true : false;
	}
	
	public function preventDefaultAction ()
	{
		$this->preventDefaultAction = true;
	}
	
	public function setButtonAction ($buttonAction = 'custom')
	{
		if (XXX_Array::hasValue(array('custom', 'submitForm', 'reset'), $buttonAction))
		{
			$this->buttonAction = $buttonAction;
		}
	}
	
	public function setButtonEmotion ($buttonEmotion = 'normal')
	{
		if (XXX_Array::hasValue(array('normal', 'premium', 'safe', 'explicit'), $buttonEmotion))
		{
			$this->presentation['buttonEmotion'] = $buttonEmotion;
		}
	}
	
	public function setLabel ($label = 'Button')
	{
		if (XXX_Type::isValue($label))
		{
			$this->label = $label;
		}
	}
			
	public function composeHTML ()
	{
		$html = '';
		
		switch ($this->getAttachedFormComposeSide())
		{
			case 'server':
					$tempAttributes = array
					(
						array('key' => 'ID', 'value' => $this->ID . '_nativeButton'),
						array('key' => 'name', 'value' => $this->name),
						array('key' => 'buttonAction', 'value' => $this->buttonAction),
						array('key' => 'readOnly', 'value' => !$this->isEditable()),
						array('key' => 'disabled', 'value' => $this->isDisabled())
					);
					
					$composedNativeButton = XXX_HTML_Composer::composeNativeButton($tempAttributes, $this->label);
				
				$html .= $composedNativeButton;
						
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
													
		$js .= $jsInstanceVariable . '.setButtonAction(' . XXX_Composer_JS::composeString($this->buttonAction) . ');' . XXX_String::$lineSeparator;											
		
		if ($this->presentation['buttonEmotion'] != 'normal')
		{
			$js .= $jsInstanceVariable . '.setButtonEmotion(' . XXX_Composer_JS::composeString($this->presentation['buttonEmotion']) . ');' . XXX_String::$lineSeparator;
		}
		
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