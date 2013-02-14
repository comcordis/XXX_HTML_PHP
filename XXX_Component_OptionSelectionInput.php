<?php


/*

$option = array
(
	'value' => 'value'
	'textLabel' => 'label',
	'htmlLabel' => '<b>label</b>',
	'selected' => false
);

// TODO option groups, block multiple option functions for exclusive and vice-versa

*/

abstract class XXX_Component_OptionSelectionInput extends XXX_Component_Input
{
	public $CLASS_NAME = 'XXX_Component_OptionSelectionInput';
	
	protected $inputType = 'optionSelectionInput';
		
	protected $options = array();
	
	protected $selectedOptionValues = array();
	
	// exclusive (one) | free (multiple)
	protected $selectionMode = 'exclusive';
	
	public function __construct ($ID = '', $name = '', $nativeForm = false)
	{
		parent::__construct($ID, $name, $nativeForm);
	}
	
	public function setSelectionMode ($selectionMode = 'exclusive')
	{
		if ($selectionMode == 'exclusive' || $selectionMode == 'free')
		{
			$this->selectionMode = $selectionMode;
		}
	}
	
	public function getSelectionMode ()
	{
		return $this->selectionMode;
	}
	
	
	public function processActionsSub ($eventTrigger = '')
	{
		$validated = true;
		$operated = false;
		
		if ($this->malicious)
		{
			$operated = true;
			$this->addFeedbackMessage('operation', XXX_I18n_Translation::get('input', 'filter', 'feedbackMessages', $this->malicious));
		}
		
		switch ($this->inputType)
		{
			case 'exclusiveOptionListBoxInput':
			case 'exclusiveOptionSwitchListInput':
			case 'freeOptionListBoxInput':
			case 'freeOptionSwitchListInput':
				$selectedOptionValues = $this->getSelectedOptionValues();
				$deselectedOptionValues = array();
				
				for ($i = 0, $iEnd = XXX_Array::getFirstLevelItemTotal($this->flatActionList); $i < $iEnd; ++$i)
				{
					$action = $this->flatActionList[$i];	
					
					switch ($action['actionType'])
					{
						case 'operation':
							$actionResponse = XXX_Client_Input::operateOnOptions($selectedOptionValues, $action['action'], $action['texts'], $action['parameters'], $eventTrigger);
						
							if ($actionResponse)
							{									
								if ($actionResponse['operated'])
								{
									$operated = true;
									$selectedOptionValues = $actionResponse['selectedOptionValues'];
									$deselectedOptionValues = $actionResponse['deselectedOptionValues'];
																															
									$this->addFeedbackMessage('operation', $actionResponse['feedbackMessage']);
								}
							}
							break;
						case 'validation':
							if ($validated)
							{											
								$actionResponse = XXX_Client_Input::validateOptions($selectedOptionValues, $action['action'], $action['texts'], $action['parameters'], $eventTrigger);
							
								if ($actionResponse)
								{									
									if (!$actionResponse['validated'])
									{													
										$validated = false;
										
										$this->addFeedbackMessage('validation', $actionResponse['feedbackMessage']);
									}
								}
							}
							break;
						case 'confirmation':
							if ($validated && $this->hasActions('validation'))
							{
								$this->addFeedbackMessage('confirmation', $action['texts']);
							}
							break;
						case 'information':
							$actionResponse = XXX_Client_Input::informAboutOptions($selectedOptionValues, $action['action'], $action['texts'], $action['parameters'], $eventTrigger);
						
							if ($actionResponse)
							{
								if ($actionResponse['informed'])
								{
									$this->addFeedbackMessage('information', $actionResponse['feedbackMessage']);
								}
							}
							break;
					}
				}
				
				if ($operated)
				{
					$deselectedOptionValueTotal = XXX_Array::getFirstLevelItemTotal($deselectedOptionValues);
					
					if ($deselectedOptionValueTotal)
					{
						for ($i = 0, $iEnd = $deselectedOptionValueTotal; $i < $iEnd; ++$i)
						{
							$this->deselectOptionByValue($deselectedOptionValues[$i]);
						}
					}
				}
				break;
		}
		
		return $validated;
	}
	
	// options
	
		public function resetOptions ()
		{
			$this->selectedOptionValues = array();
			
			$this->options = array();
		}
		
		// Value
		public function doesOptionExist (array $option = array())
		{
			$exist = false;
			
			foreach ($this->options as $tempOption)
			{
				// TODO potentially check more...
				if ($tempOption['value'] == $option['value'])
				{
					$exist = true;
					
					break;
				}
			}
			
			return $exist;
		}
		
		public function isValidOption (array $option = array())
		{
			$valid = false;
			
			if (XXX_Type::isAssociativeArray($option) && XXX_Type::isValue($option['textLabel']) && XXX_Type::isValue($option['value']))
			{
				$valid = true;
			}
			
			return $valid;
		}
		
		public function addOption ($option = array())
		{
			$result = false;
			
			if ($this->isValidOption($option) && !$this->doesOptionExist($option))
			{
				if (XXX_Type::isEmpty($option['textLabel']))
				{
					$option['textLabel'] = '';
				}
				
				if (XXX_Type::isEmpty($option['htmlLabel']))
				{
					$option['htmlLabel'] = $option['textLabel'];
				}
				
				
				$option['selected'] = $option['selected'] ? true : false;
				
				if ($option['selected'])
				{
					if ($this->selectionMode == 'free')
					{
						$this->selectedOptionValues[] = $option['value'];
					}
					else
					{
						$this->selectedOptionValues = array($option['value']);	
					}
				}
				
				$this->options[] = $option;
				
				$result = true;
			}
			
			return $result;
		}
		
		public function addOptions ($options = array())
		{
			foreach ($options as $option)
			{
				$this->addOption($option);
			}
		}
		
		public function setOptions ($options = array())
		{
			$this->resetOptions();
			
			foreach ($options as $option)
			{
				$this->addOption($option);
			}
		}
						
		public function getOptions ()
		{
			return $this->options;	
		}
		
		public function setOption ($option = array())
		{
			$this->resetOptions();
			
			$this->addOption($option);
		}
		
		public function getOption ()
		{
			$result = false;
			
			if (XXX_Type::isFilledArray($this->options))
			{
				$result = $this->options[0];
			}
			
			return $result;
		}
		
		public function getOptionIndexByProperty ($property = 'value', $value = '')
		{
			$result = false;
			
			for ($i = 0, $iEnd = XXX_Array::getFirstLevelItemTotal($this->options); $i < $iEnd; ++$i)
			{
				$option = $this->options[$i];
				
				if ($property == 'index')
				{
					if ($i == $value)
					{
						$result = $i;
						break;
					}
				}
				else
				{				
					if ($option[$property] == $value)
					{
						$result = $i;
						break;
					}
				}
			}
			
			return $result;
		}
	
	// get selected
	
		public function getSelectedOptionValue ()
		{
			$result = false;
			
			if ($this->selectionMode == 'exclusive' && XXX_Array::getFirstLevelItemTotal($this->selectedOptionValues))
			{
				$result = $this->selectedOptionValues[0];
			}
			
			return $result;
		}
		
		public function getSelectedOptionValues ()
		{
			return $this->selectedOptionValues;	
		}
		
		public function getSelectedOptionPropertyValue ($property = 'textLabel')
		{
			$result = false;
			
			$selectedOptionPropertyValues = $this->getSelectedOptionPropertyValues($property);
			
			if (XXX_Type::isFilledArray($selectedOptionPropertyValues))
			{
				$result = $selectedOptionPropertyValues[0];
			}
			
			return $result;
		}
		
		public function getSelectedOptionPropertyValues ($property = 'textLabel')
		{
			$result = array();
			
			if (XXX_Type::isFilledArray($this->selectedOptionValues))
			{
				for ($i = 0, $iEnd = XXX_Array::getFirstLevelItemTotal($this->selectedOptionValues); $i < $iEnd; ++$i)
				{
					$index = $this->getOptionIndexByProperty('value', $this->selectedOptionValues[$i]);
					
					$option = $this->options[$index];
										
					if ($option)
					{
						if ($property == 'index')
						{
							$result[] = $index;
						}
						else
						{
							$result[] = $option[$property];
						}
					}
				}
			}
			
			return $result;
		}
		
	// deselect
		
		public function deselectOptionByProperty ($property = 'value', $value = '')
		{
			$result = false;
			
			$index = $this->getOptionIndexByProperty($property, $value);
			
			if ($index !== false)
			{
				$option = $this->options[$index];
				
				if ($option)
				{
					if ($option['selected'] == true)
					{
						$this->options[$index]['selected'] = false;
						
						if ($this->selectionMode == 'free')
						{					
							if (XXX_Array::hasValue($this->selectedOptionValues, $option['value']))
							{
								$newSelectedOptionValues = array();
								
								foreach ($this->selectedOptionValues as $selectedOptionValue)
								{
									if ($selectedOptionValue !== $option['value'])
									{
										$newSelectedOptionValues[] = $selectedOptionValue;
									}
								}
								
								$this->selectedOptionValues = $newSelectedOptionValues;
							}
						}
						else
						{
							$this->selectedOptionValues = array();
						}
						
						$result = true;						
					}
					else
					{
						$result = true;
					}
				}
			}
			
			return $result;
		}
		
		public function deselectOptionByValue ($value = '')
		{
			return $this->deselectOptionByProperty('value', $value);
		}
		
		public function deselectFirstSelectedOption ()
		{
			$result = false;
			
			if (XXX_Type::isFilledArray($this->selectedOptionValues))
			{
				$firstSelectedOptionValue = $this->selectedOptionValues[0];
								
				if ($firstSelectedOptionValue)
				{
					$result = $this->deselectOptionByProperty('value', $firstSelectedOptionValue);
				}
			}
			else
			{
				$result = true;
			}
			
			return $result;
		}
		
		public function deselectLastSelectedOption ()
		{
			$result = false;
			
			if (XXX_Type::isFilledArray($this->selectedOptionValues))
			{
				$lastSelectedOptionValue = $this->selectedOptionValues[XXX_Array::getFirstLevelItemTotal($this->selectedOptionValues)  - 1];
			
				if ($lastSelectedOptionValue)
				{
					$result = $this->deselectOptionByProperty('value', $lastSelectedOptionValue);
				}
			}
			else
			{
				$result = true;
			}
			
			return $result;
		}
		
		public function deselectSelectedOption ()
		{
			return $this->deselectFirstSelectedOption();
		}
	
		public function deselectAllOptions ()
		{
			$this->selectedOptionValues = array();
			
			for ($i = 0, $iEnd = XXX_Array::getFirstLevelItemTotal($this->options); $i < $iEnd; ++$i)
			{
				$this->options[$i]['selected'] = false;
			}
		}		
		
		public function areAllOptionsDeselected ()
		{
			return XXX_Array::getFirstLevelItemTotal($this->selectedOptionValues) == XXX_Array::getFirstLevelItemTotal($this->options);
		}
		
		public function isOptionDeselectedByProperty ($property = 'value', $value = '')
		{
			$result = false;
			
			$index = $this->getOptionIndexByProperty($property, $value);
			
			if ($index !== false)
			{
				$option = $this->options[$index];
				
				if ($option)
				{
					if ($option['selected'] == false)
					{
						$result = true;						
					}
				}
			}
			
			return $result;
		}
		
		public function isOptionDeselectedByValue ($value = '')
		{
			return $this->isOptionDeselectedByProperty('value', $value);
		}
		
	// select
	
		public function selectOptionByProperty ($property = 'value', $value = '')
		{
			$result = false;
			
			$index = $this->getOptionIndexByProperty($property, $value);
			
			if ($index !== false)
			{
				$option = $this->options[$index];
				
				if ($option)
				{
					if ($option['selected'] == false)
					{
						$this->options[$index]['selected'] = true;
						
						if ($this->selectionMode == 'free')
						{
							if (!XXX_Array::hasValue($this->selectedOptionValues, $option['value']))
							{
								$this->selectedOptionValues[] = $option['value'];
							}
						}
						else
						{
							$this->deselectSelectedOption();
							
							$this->selectedOptionValues = array($option['value']);
						}
						
						$result = true;
					}
					else
					{
						$result = true;
					}
				}
			}
			
			return $result;
		}
		
		public function selectOptionByValue ($value = '')
		{
			return $this->selectOptionByProperty('value', $value);
		}
		
		public function selectAllOptions ()
		{
			if ($this->selectionMode == 'free')
			{
				$this->selectedOptionValues = array();
			
				for ($i = 0, $iEnd = XXX_Array::getFirstLevelItemTotal($this->options); $i < $iEnd; ++$i)
				{
					$this->options[$i]['selected'] = true;
					
					$this->selectedOptionValues[] = $this->options[$i]['value'];
				}
			}
		}
		
		public function areAllOptionsSelected ()
		{
			return XXX_Array::getFirstLevelItemTotal($this->selectedOptionValues) == 0;
		}
		
		public function isOptionSelectedByProperty ($property = 'value', $value = '')
		{
			$result = false;
			
			$index = $this->getOptionIndexByProperty($property, $value);
			
			if ($index !== false)
			{
				$option = $this->options[$index];
				
				if ($option)
				{
					if ($option['selected'] == true)
					{
						$result = true;						
					}
				}
			}
			
			return $result;
		}
		
		public function isOptionSelectedByValue ($value = '')
		{
			return $this->isOptionSelectedByProperty('value', $value);
		}
		
	// invert
	
		public function invertSelectAllOptions ()
		{
			if ($this->selectionMode == 'free')
			{
				$this->selectedOptionValues = array();
			
				for ($i = 0, $iEnd = XXX_Array::getFirstLevelItemTotal($this->options); $i < $iEnd; ++$i)
				{
					if ($this->options[$i]['selected'])
					{
						$this->options[$i]['selected'] = false;
					}
					else
					{
						$this->options[$i]['selected'] = true;
						
						$this->selectedOptionValues[] = $this->options[$i]['value'];
					}
				}
			}
		}
	
	// Order
			
		public function orderOptionsByProperty ($property = 'textLabel', $order = 'ascending')
		{
			$newOptions = array();
			
			$oldOptions = $this->options;
			
			if ($property == 'textLabel' && $order == 'ascending')
			{
				usort($oldOptions, array('XXX_Component_OptionSelectionInput', compareOptionsByTextLabelAscending));			
			}
			else if ($property == 'textLabel' && $order == 'descending')
			{
				usort($oldOptions, array('XXX_Component_OptionSelectionInput', compareOptionsByTextLabelDescending));	
			}
			else if ($property == 'value' && $order == 'ascending')
			{
				usort($oldOptions, array('XXX_Component_OptionSelectionInput', compareOptionsByTextLabelAscending));	
			}
			else if ($property == 'value' && $order == 'descending')
			{
				usort($oldOptions, array('XXX_Component_OptionSelectionInput', compareOptionsByTextLabelDescending));	
			}
						
			$newOptions = $oldOptions;
			
			$this->options = $newOptions;
		}
		
		public function orderOptions ()
		{
			return $this->orderOptionsByProperty('textLabel', 'ascending');
		}
	
		public static function compareOptionsByTextLabelAscending ($optionA, $optionB)
		{
			return ($optionA['textLabel'] == $optionB['textLabel']) ? 0 : (($optionA['textLabel'] < $optionB['textLabel']) ? -1 : 1);
		}
		
		public static function compareOptionsByTextLabelDescending ($optionA, $optionB)
		{
			return ($optionB['textLabel'] == $optionA['textLabel']) ? 0 : (($optionB['textLabel'] < $optionA['textLabel']) ? -1 : 1);
		}
		
		public static function compareOptionsByValueAscending ($optionA, $optionB)
		{
			return ($optionA['value'] == $optionB['value']) ? 0 : (($optionA['value'] < $optionB['value']) ? -1 : 1);
		}
		
		public static function compareOptionsByValueDescending ($optionA, $optionB)
		{
			return ($optionB['value'] == $optionA['value']) ? 0 : (($optionB['value'] < $optionA['value']) ? -1 : 1);
		}
}


?>