<?php



class XXX_Component_ExclusiveOptionSwitchInput extends XXX_Component_OptionSwitchInput
{
	public $CLASS_NAME = 'XXX_Component_ExclusiveOptionSwitchInput';
	
	protected $inputType = 'exclusiveOptionSwitchInput';
				
	public function composeHTML ()
	{
		$html = '';
			
		switch ($this->getAttachedFormComposeSide())
		{
			case 'server':
					$tempAttributes = array
					(
						array('key' => 'ID', 'value' => $this->ID . '_nativeExclusiveOptionSwitchInput'),
						array('key' => 'name', 'value' => $this->name),
						array('key' => 'readOnly', 'value' => !$this->isEditable()),
						array('key' => 'disabled', 'value' => $this->isDisabled()),
						array('key' => 'selected', 'value' => $this->isSelected())
					);
					
				$html .= XXX_HTML_Composer::composeNativeExclusiveOptionSwitchInput($tempAttributes, $this->getValue(), $this->isSelected());
				
				$html .= XXX_HTML_Composer::composeInputLabel(false, $this->ID . '_inputLabel', $this->ID . '_nativeExclusiveOptionSwitchInput', $this->htmlLabel);
					
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
		
		$js .= $jsInstanceVariable . '.setValue(' .XXX_JS_Composer::composeString($this->getValue()) . ');' . XXX_String::$lineSeparator;
		$js .= $jsInstanceVariable . '.setLabel(' . XXX_JS_Composer::composeString($this->textLabel) . ', ' . XXX_JS_Composer::composeString($this->htmlLabel) . ');' . XXX_String::$lineSeparator;		
		
		if ($this->isSelected())
		{
			$js .= $jsInstanceVariable . '.setSelected(true);' . XXX_String::$lineSeparator;
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
		
		$js .= parent::composeElementsReadyJS();
		
		return $js;
	}
	
}



?>