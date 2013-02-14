<?php


class XXX_Component_FreeOptionSwitchListInput extends XXX_Component_OptionSelectionInput
{
	public $CLASS_NAME = 'XXX_Component_FreeOptionSwitchListInput';
	
	protected $inputType = 'freeOptionSwitchListInput';
	
	protected $selectionMode = 'free';
	
	public function __construct ($ID = '', $name = '')
	{
		parent::__construct($ID, $name);
		
		$this->setPresentation(array(
			'columns' => 1
		));
		
		$this->elements['freeOptionSwitchInputs'] = array();
	}
	
	public function setPresentation (array $presentation = array())
	{
		parent::setPresentation($presentation);
						
		$this->presentation['columns'] = XXX_Default::toPositiveInteger($this->presentation['columns'], 1);
	}
	
	protected function createFreeOptionSwitchInputs ()
	{
		$options = $this->getOptions();
		
		for ($i = 0, $iEnd = XXX_Array::getFirstLevelItemTotal($options); $i < $iEnd; ++$i)
		{
			$option = $options[$i];
			
			$freeOptionSwitchInput = new XXX_Component_FreeOptionSwitchInput($this->ID . '_' . $i, $this->name);
			$freeOptionSwitchInput->attachForm($this->elements['form']);
			$freeOptionSwitchInput->setValue($option['value']);
			$freeOptionSwitchInput->setLabel($option['textLabel'], $option['htmlLabel']);
			$freeOptionSwitchInput->setSelected($option['selected']);
			$freeOptionSwitchInput->setEditable($this->isEditable());
			$freeOptionSwitchInput->setDisabled($this->isDisabled());
			
			$this->elements['freeOptionSwitchInputs'][] = $freeOptionSwitchInput;
		}
	}
	
	public function composeHTML ()
	{
		$this->createFreeOptionSwitchInputs();
	
		$html = '';
		
		switch ($this->getAttachedFormComposeSide())
		{
			case 'server':
				$composedFreeOptionSwitchInputs = array();
				
				for ($i = 0, $iEnd = XXX_Array::getFirstLevelItemTotal($this->elements['freeOptionSwitchInputs']); $i < $iEnd; ++$i)
				{
					$composedFreeOptionSwitchInputs[] = $this->elements['freeOptionSwitchInputs'][$i]->composeHTML();
				}
									
				$html .= XXX_HTML_Composer::composeBasicGrid($this->ID . '_basicGrid', $composedFreeOptionSwitchInputs, $this->presentation['columns']);
				$html .= XXX_HTML_Composer::composeOptionSelectionManipulator($this->ID . '_optionSelectionManipulator');
				
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
			'selectedOptionValues' => $this->getSelectedOptionValues(),
			'feedbackMessages' => $this->feedbackMessages
		);
		
		return $asynchronousResponse;
	}
}


?>