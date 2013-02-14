<?php



class XXX_Component_HiddenVariableInput extends XXX_Component_CharacterInput
{
	public $CLASS_NAME = 'XXX_Component_HiddenVariableInput';
	
	protected $inputType = 'hiddenVariableInput';
	
	public function __construct ($ID = '', $name = '')
	{
		parent::__construct($ID, $name);
	}
	
	public function composeHTML ()
	{
		$html = '';
		
		switch ($this->getAttachedFormComposeSide())
		{
			case 'server':
					$tempAttributes = array
					(
						array('key' => 'ID', 'value' => $this->ID . '_nativeHiddenVariableInput'),
						array('key' => 'name', 'value' => $this->name),
						array('key' => 'readOnly', 'value' => !$this->isEditable()),
						array('key' => 'disabled', 'value' => $this->isDisabled())
					);
					
					$composedNativeHiddenVariableInput = XXX_HTML_Composer::composeNativeHiddenVariableInput($tempAttributes, $this->getValue());
				
				$html .= $composedNativeHiddenVariableInput;
				
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
		
		$js .= $jsInstanceVariable . '.setValue(' . XXX_JS_Composer::composeString($this->getValue()) . ');' . XXX_String::$lineSeparator;
		
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
		if (!$this->getAttachedFormClientSideActionProcessing())
		{
			$js .= $jsInstanceVariable . '.setClientSideActionProcessing(false);' . XXX_String::$lineSeparator;
		}
		
		$js .= parent::composeJS();
		
		return $js;
	}
		
	public function composeAsynchronousResponse ()
	{
		$asynchronousResponse = array
		(
			'ID' => $this->ID,
			'inputType' => $this->inputType,
			'value' => $this->getValue(),
			'disabled' => $this->isDisabled(),
			'editable' => $this->isEditable(),
			'valid' => $this->isValid(),
			'feedbackMessages' => $this->feedbackMessages
		);
		
		return $asynchronousResponse;
	}
}


?>