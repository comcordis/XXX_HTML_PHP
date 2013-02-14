<?php



abstract class XXX_Component_CharacterInput extends XXX_Component_Input
{
	public $CLASS_NAME = 'XXX_Component_CharacterInput';
	
	protected $inputType = 'characterInput';
	
	public function __construct ($ID = '', $name = '', $nativeForm = false)
	{
		parent::__construct($ID, $name, $nativeForm);
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
			case 'characterInput':
			case 'characterLineInput':
			case 'maskedCharacterLineInput':
			case 'characterLinesInput':
			case 'hiddenVariableInput':
				$value = $this->getValue();
				
				for ($i = 0, $iEnd = XXX_Array::getFirstLevelItemTotal($this->flatActionList); $i < $iEnd; ++$i)
				{
					$action = $this->flatActionList[$i];	
					
					switch ($action['actionType'])
					{
						case 'operation':
							$actionResponse = XXX_Client_Input::operateOnValue($value, $action['action'], $action['texts'], $action['parameters'], $eventTrigger);
						
							if ($actionResponse)
							{									
								if ($actionResponse['operated'])
								{
									$operated = true;
									$value = $actionResponse['value'];
																			
									$this->addFeedbackMessage('operation', $actionResponse['feedbackMessage']);
								}
							}
							break;
						case 'validation':
							if ($validated)
							{
								$actionResponse = XXX_Client_Input::validateValue($value, $action['action'], $action['texts'], $action['parameters'], $eventTrigger);
							
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
							$actionResponse = XXX_Client_Input::informAboutValue($value, $action['action'], $action['texts'], $action['parameters'], $eventTrigger);
						
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
					$this->setValue($value);
				}
				break;
		}
		
		return $validated;
	}
}


?>