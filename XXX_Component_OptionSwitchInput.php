<?php


abstract class XXX_Component_OptionSwitchInput extends XXX_Component_Input
{
	public $CLASS_NAME = 'XXX_Component_OptionSwitchInput';
	
	protected $inputType = 'optionSwitchInput';
	
	protected $textLabel = '';
	protected $htmlLabel = '';
		
	public function __construct ($ID = '', $name = '', $nativeForm = false)
	{
		parent::__construct($ID, $name, $nativeForm);
	}
	
	public function setLabel ($textLabel = '', $htmlLabel = '')
	{
		if (XXX_Type::isEmpty($htmlLabel))
		{
			$htmlLabel = $textLabel;
		}
		
		$this->textLabel = $textLabel;
		$this->htmlLabel = $htmlLabel;
	}
	
	public function getOption ()
	{
		$option = array
		(
			'value' => $this->getValue(),
			'selected' => $this->isSelected()
		);
		
		return $option;
	}
		
	public function composeAsynchronousResponse ()
	{
		$asynchronousResponse = array
		(
			'ID' => $this->ID,
			'inputType' => $this->inputType,
			'disabled' => $this->isDisabled(),
			'valid' => $this->isValid(),
			'selected' => $this->isSelected(),
			'feedbackMessages' => $this->feedbackMessages
		);
		
		return $asynchronousResponse;
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
			case 'exclusiveOptionSwitchInput':
			case 'freeOptionSwitchInput':
				$option = $this->getOption();
				
				for ($i = 0, $iEnd = XXX_Array::getFirstLevelItemTotal($this->flatActionList); $i < $iEnd; ++$i)
				{
					$action = $this->flatActionList[$i];	
					
					switch ($action['actionType'])
					{
						case 'operation':
							$actionResponse = XXX_Client_Input::operateOnOption($option, $action['action'], $action['texts'], $action['parameters'], $eventTrigger);
						
							if ($actionResponse)
							{									
								if ($actionResponse['operated'])
								{
									$operated = true;
									$option = $actionResponse['option'];
																			
									$this->addFeedbackMessage('operation', $actionResponse['feedbackMessage']);
								}
							}
							break;
						case 'validation':
							if ($validated)
							{
								$actionResponse = XXX_Client_Input::validateOption($option, $action['action'], $action['texts'], $action['parameters'], $eventTrigger);
							
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
							$actionResponse = XXX_Client_Input::informAboutOption($option, $action['action'], $action['texts'], $action['parameters'], $eventTrigger);
						
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
					$this->setSelected($option['selected']);
				}
				break;
		}
		
		return $validated;
	}
}


?>