<?php

class XXX_Component_DatePickerInput extends XXX_Component_Input
{
	public $CLASS_NAME = 'XXX_Component_DatePickerInput';
	
	protected $inputType = 'datePickerInput';
					
	public function __construct ($ID = '', $name = '')
	{
		parent::__construct($ID, $name);
		
		$this->setPresentation(array(
			'showFeedbackMessagesAlwaysAsBlock' => true,
			'inputFieldIcon' => 'Calendar'
		));
	}
	
	public function processActionsSub ($eventTrigger = '')
	{
		//$this->propagateDate('up', true);
		
		$validated = ($this->elements['year']->isValid() && $this->elements['month']->isValid() && $this->elements['date']->isValid());
		$operated = false;
		
		$this->elements['year']->propagateFeedbackMessagesUp();
		$this->elements['month']->propagateFeedbackMessagesUp();
		$this->elements['date']->propagateFeedbackMessagesUp();
				
		if ($this->malicious)
		{
			$operated = true;
			$this->addFeedbackMessage('operation', XXX_I18n_Translation::get('input', 'filter', 'feedbackMessages', $this->malicious));
		}
		
		switch ($this->inputType)
		{
			case 'datePickerInput':
				$date = $this->getDate();
				
				for ($i = 0, $iEnd = XXX_Array::getFirstLevelItemTotal($this->flatActionList); $i < $iEnd; ++$i)
				{
					$action = $this->flatActionList[$i];
					
					$actionResponse = false;
					
					switch ($action['actionType'])
					{
						case 'validation':
							if ($validated)
							{
								$actionResponse = XXX_Client_Input::validateDate($date, $action['action'], $action['texts'], $action['parameters'], $eventTrigger);
								
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
						case 'information':
							$actionResponse = XXX_Client_Input::informAboutDate($date, $action['action'], $action['texts'], $action['parameters'], $eventTrigger);
							
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
								
				if (operated)
				{
					
				}
				break;
		}
		
		return $validated;
	}
	
	public function childProcessedActions ($eventTrigger = '')
	{
		$this->propagateDate('up');
	}
	
	public function propagateDate ($direction, $doNotTriggerProcessActions)
	{
		$direction = XXX_Default::toOption($direction, array('up', 'down'), 'up');
						
		switch ($direction)
		{
			case 'up':
				$date = array
				(
					'year' => XXX_Type::makeInteger($this->elements['year']->getValue()),
					'month' => XXX_Type::makeInteger($this->elements['month']->getSelectedOptionValue()),
					'date' => XXX_Type::makeInteger($this->elements['date']->getSelectedOptionValue())
				);
				
				$this->changeState('date', $date, !$doNotTriggerProcessActions);
				break;
			case 'down':
				$date = $this->getDate();
				
				$this->elements['year']->setValue($date['year']);
				$this->elements['month']->selectOptionByValue($date['month']);
				$this->elements['date']->selectOptionByValue($date['date']);		
				break;
		}
	}
	
	public function getTimestamp ()
	{
		$date = $this->getDate();
		
		$timestamp = new XXX_Timestamp(array('year' => $date['year'], 'month' => $date['month'], 'date' => $date['date'], 'hour' => 0, 'minute' => 0, 'second' => 0));
					
		return $timestamp->get();
	}
	
	public function setTimestamp ($timestamp)
	{
		$timestamp = new XXX_Timestamp($timestamp);
		
		$timestampParts = $timestamp->parse();
		
		$date = array
		(
			'year' => $timestampParts['year'],
			'month' => $timestampParts['month'],
			'date' => $timestampParts['date']
		);
		
		return $this->setDate($date);
	}
	
	public function setDate ($date)
	{
		parent::setDate($date);
				
		$this->propagateDate('down');
	}
		
	public function setup ()
	{
		$date = $this->getDate();
		
		$days = array();
		
		for ($i = 1, $iEnd = 31; $i <= $iEnd; ++$i)
		{
			$selected = $i == $date['date'];
			
			$days[] = array('value' => $i, 'textLabel' => $i, 'htmlLabel' => $i , 'selected' => $selected);
		}
		
		$this->elements['date'] = new XXX_Component_ExclusiveOptionListBoxInput($this->ID . '_date');		
			$this->elements['date']->attachForm($this->elements['form']);		
			$this->elements['date']->attachParentInput($this);
			$this->elements['date']->setFilter('integer');
			$this->elements['date']->setPresentation(array('feedbackIcon' => false, 'inputField' => 'parent'));
			$this->elements['date']->setOptions($days);
				$this->elements['date']->addAction('validation', 'required', '', false);
		
		$monthNames = XXX_I18n_Translation::get('dateTime', 'months', 'names');
		
		$months = array();
		
		for ($i = 1, $iEnd = 12; $i <= $iEnd; ++$i)
		{
			$monthName = $monthNames[$i - 1];
			
			$selected = $i == $date['month'];
			
			$months[] = array('value' => $i, 'textLabel' => $monthName, 'htmlLabel' => $monthName, 'selected' => $selected);
		}
		
		$this->elements['month'] = new XXX_Component_ExclusiveOptionListBoxInput($this->ID . '_month');		
			$this->elements['month']->attachForm($this->elements['form']);		
			$this->elements['month']->attachParentInput($this);
			$this->elements['month']->setFilter('integer');
			$this->elements['month']->setPresentation(array('feedbackIcon' => false, 'inputField' => 'parent'));
			$this->elements['month']->setOptions($months);
				$this->elements['month']->addAction('validation', 'required', '', false);
		
		$this->elements['year'] = new XXX_Component_CharacterLineInput($this->ID . '_year');		
			$this->elements['year']->attachForm($this->elements['form']);
			$this->elements['year']->attachParentInput($this);
			$this->elements['year']->setFilter('integer');
			$this->elements['year']->setPresentation(array('feedbackIcon' => false, 'inputField' => 'parent'));
			$this->elements['year']->setExampleValue('e.g. 2012');
			$this->elements['year']->setValue($date['year']);
			$this->elements['year']->setAlign('left');
			$this->elements['year']->makeElastic(4, 6);
				$this->elements['year']->addAction('operation', 'integer', 'Made integer.', false);
				$this->elements['year']->addAction('validation', 'required', 'An existing year is required.', false);
				$this->elements['year']->addAction('validation', 'integer', 'An existing integer year is required.', false);
				$this->elements['year']->selectAllOnFocus();
	}
	
	public function composeHTML ()
	{
		$html = '';
		
		$html .= XXX_HTML_Composer::composeNativeInlineContainer($this->ID . '_dayOfTheWeek') . ' ';
		
		$html .= $this->elements['date']->composeHTML() . ' ' . $this->elements['month']->composeHTML() . ' ' . $this->elements['year']->composeHTML();
		
		$composedCalendar = XXX_HTML_Composer::composeNativeInlineContainer($this->ID . '_calendar');
		
		if ($this->presentation['inputField'] == 'self')
		{
			$html .= $this->composeFeedbackIconHTML();
			
			$html .= $composedCalendar;
			
			$html = XXX_HTML_Composer::composeInputField($this->ID . '_inputField', $html, 'camoflaged', $this->presentation['inputFieldIcon']);
			
			$html .= $this->composeFeedbackMessagesHTML();
		}
		else
		{
			$html .= $composedCalendar;
			
			$html .= $this->composeFeedbackHTML();
		}
		
		return $html;
	}
	
	public function composeJS ()
	{
		$js = '';
			
		$jsInstanceVariable = $this->getJSInstanceVariable();
		
		$js .= parent::composeInitializationJS();
			
		$js .= $this->elements['date']->composeJS();
		$js .= $this->elements['month']->composeJS();
		$js .= $this->elements['year']->composeJS();
		
		$js .= parent::composeJS();
		
		return $js;
	}
}

?>