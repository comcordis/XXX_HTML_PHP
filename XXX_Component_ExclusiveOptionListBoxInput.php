<?php


class XXX_Component_ExclusiveOptionListBoxInput extends XXX_Component_OptionSelectionInput
{
	public $CLASS_NAME = 'XXX_Component_ExclusiveOptionListBoxInput';
	
	protected $inputType = 'exclusiveOptionListBoxInput';
	
	protected $selectionMode = 'exclusive';
		
	public function __construct ($ID = '', $name = '')
	{
		parent::__construct($ID, $name);
		
		$this->setPresentation(array(
			'align' => 'left'
		));
	}
	public function setPresentation (array $presentation = array())
	{
		parent::setPresentation($presentation);
		
		$this->presentation['align'] = XXX_Default::toOption($this->presentation['align'], array('left', 'center', 'right'), 'left');
	}
	
	public function setAlign ($align = 'left')
	{
		if ($align == 'left' || $align == 'center' || $align == 'right')
		{
			$this->presentation['align'] = $align;
		}
	}
	
	public function composeHTML ()
	{
		$html = '';
			
		switch ($this->getAttachedFormComposeSide())
		{
			case 'server':
				$options = $this->getOptions();
			
					$composedNativeOptionInputs = '';
					
					for ($i = 0, $iEnd = XXX_Array::getFirstLevelItemTotal($options); $i < $iEnd; ++$i)
					{
						$option = $options[$i];
						$option['index'] = $i;
						
							$nativeOptionInputID = $this->ID . '_nativeOptionInput_' . $option['index'];
							
							$attributes = array
							(
								array('key' => 'ID', 'value' => $nativeOptionInputID),
								array('key' => 'style', 'value' => ('text-align: ' . $this->presentation['align']))
							);
							
						$composedNativeOptionInputs .= XXX_HTML_Composer::composeNativeOptionInput($attributes, $option['value'], $option['textLabel'], $option['selected']);
					}
					
					$nativeExclusiveOptionListBoxInputID = $this->ID . '_nativeExclusiveOptionListBoxInput';
										
					$attributes = array
					(
						array('key' => 'ID', 'value' => $nativeExclusiveOptionListBoxInputID),
						array('key' => 'name', 'value' => $this->name),
						array('key' => 'readOnly', 'value' => !$this->isEditable()),
						array('key' => 'disabled', 'value' => $this->isDisabled()),
						array('key' => 'style', 'value' => ('text-align: ' . $this->presentation['align']))
					);
				
				$html .= XXX_HTML_Composer::composeNativeExclusiveOptionListBoxInput($attributes, $composedNativeOptionInputs);
								
				if ($this->presentation['inputField'] == 'self')
				{
					$html .= $this->composeFeedbackIconHTML();
					
					$html = XXX_HTML_Composer::composeInputField($this->ID . '_inputField', $html);
					
					$html .= $this->composeFeedbackMessagesHTML();
				}
				else
				{
					$html .= $this->composeFeedbackHTML();
				}
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
		
		$js .= $jsInstanceVariable . '.setOptions(' . XXX_String_JSON::encode($this->getOptions()) . ');' . XXX_String::$lineSeparator;

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
	
	public function composeAsynchronousResponse ()
	{
		$asynchronousResponse = array
		(
			'ID' => $this->ID,
			'inputType' => $this->inputType,
			'disabled' => $this->isDisabled(),
			'valid' => $this->isValid(),
			'selectedOptionValue' => $this->getSelectedOptionValue(),
			'feedbackMessages' => $this->feedbackMessages
		);
		
		return $asynchronousResponse;
	}
}	


?>