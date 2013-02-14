<?php


class XXX_Component_ExclusiveOptionSwitchListInput extends XXX_Component_OptionSelectionInput
{
	public $CLASS_NAME = 'XXX_Component_ExclusiveOptionSwitchListInput';
	
	protected $inputType = 'exclusiveOptionSwitchListInput';
	
	protected $selectionMode = 'exclusive';
		
	public function __construct ($ID = '', $name = '')
	{
		parent::__construct($ID, $name);
		
		$this->setPresentation(array(
			'columns' => 1
		));
		
		$this->elements['exclusiveOptionSwitchInputs'] = array();
	}
	
	public function setPresentation (array $presentation = array())
	{
		parent::setPresentation($presentation);
						
		$this->presentation['columns'] = XXX_Default::toPositiveInteger($this->presentation['columns'], 1);
	}
	
	protected function createExclusiveOptionSwitchInputs ()
	{
		$options = $this->getOptions();
		
		for ($i = 0, $iEnd = XXX_Array::getFirstLevelItemTotal($options); $i < $iEnd; ++$i)
		{
			$option = $options[$i];
			
			$exclusiveOptionSwitchInput = new XXX_Component_ExclusiveOptionSwitchInput($this->ID . '_' . $i, $this->name);
			$exclusiveOptionSwitchInput->attachForm($this->elements['form']);
			$exclusiveOptionSwitchInput->setValue($option['value']);
			$exclusiveOptionSwitchInput->setLabel($option['textLabel'], $option['htmlLabel']);
			$exclusiveOptionSwitchInput->setSelected($option['selected']);
			$exclusiveOptionSwitchInput->setEditable($this->isEditable());
			$exclusiveOptionSwitchInput->setDisabled($this->isDisabled());
			
			$this->elements['exclusiveOptionSwitchInputs'][] = $exclusiveOptionSwitchInput;
		}
	}
	
	public function composeHTML ()
	{
		$this->createExclusiveOptionSwitchInputs();
		
		$html = '';
		
		switch ($this->getAttachedFormComposeSide())
		{
			case 'server':
				$composedExclusiveOptionSwitchInputs = array();
				
				for ($i = 0, $iEnd = XXX_Array::getFirstLevelItemTotal($this->elements['exclusiveOptionSwitchInputs']); $i < $iEnd; ++$i)
				{
					$composedExclusiveOptionSwitchInputs[] = $this->elements['exclusiveOptionSwitchInputs'][$i]->composeHTML();
				}
									
				$html .= XXX_HTML_Composer::composeBasicGrid($this->ID . '_basicGrid', $composedExclusiveOptionSwitchInputs, $this->presentation['columns']);
				
				$html .= $this->composeFeedbackHTML();
			break;
			case 'client':
				$html .= XXX_HTML_Composer::composeNativeBlockContainer($this->ID . '_container');
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