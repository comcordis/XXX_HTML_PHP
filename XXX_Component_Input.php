<?php

abstract class XXX_Component_Input extends XXX_Manager_State
{
	public $CLASS_NAME = 'XXX_Component_Input';
	
	protected $inputType = 'input';
	
	public static $instanceCounter = 0;

	protected $ID = '';
	protected $name = '';
	
	protected $presentation = array
	(
		'feedbackIcon' => true,
		'feedbackIconConfirmation' => false,
		'borderConfirmation' => false,
		'showFeedbackMessagesAlwaysAsBlock' => true,
		'inputField' => 'self'
	);
	
	public $processingState = 'blank';
	
	protected $composeSide = 'server';	
	protected $clientSideActionProcessing = true;
	protected $localJSVariable = false;
	
	protected $actions = array
	(
		'operation' => array(),
		'validation' => array(),
		'information' => array(),
		'confirmation' => array()
	);
	protected $flatActionList = array();
	protected $convertedActionTypeListsToFlatActionList = false;
	
	protected $feedbackMessages = array
	(
		'operation' => array(),
		'validation' => array(),
		'information' => array(),
		'confirmation' => array()
	);
		
	protected $filter = 'integer';	
	protected $malicious = false;
	
	public $elements = array();
					
	public function __construct ($ID = '', $name = '')
	{
		if (!XXX_Type::isValue($ID))
		{
			$ID = 'XXX_Component_Input_' . ++self::$instanceCounter;
		}
		
		if (!XXX_Type::isValue($name))
		{
			$name = $ID;
		}
		
		$this->ID = $ID;
		$this->name = $name;
	}
	
	// ID
	
		public function setID ($ID = '')
		{
			return $this->ID = $ID;
		}
		
		public function getID ()
		{
			return $this->ID;
		}
		
	// name
		
		public function setName ($name = '')
		{
			return $this->name = $name;
		}
		
		public function getName ()
		{
			return $this->name;
		}
	
	// nativeForm
		
		public function attachForm ($form, $notMutual = false)
		{
			if ($form)
			{
				$this->elements['form'] = $form;
				
				if (!$notMutual)
				{
					$this->elements['form']->attachInput($this);
				}
			}
		}
		
		public function getAttachedForm ()
		{
			return $this->elements['form'];
		}
	
	// parentInput
		
		public function attachParentInput ($parentInput)
		{
			if ($parentInput)
			{
				$this->elements['parentInput'] = $parentInput;
			}
		}
		
		public function getAttachedParentInput ()
		{
			return $this->elements['parentInput'];
		}	
	
	// presentation
	
		public function setPresentation (array $presentation = array())
		{
			if (XXX_Type::isAssociativeArray($presentation))
			{
				$this->presentation = XXX_Array::merge($this->presentation, $presentation);
			}
		}
	
	// filter
		
		public function setFilter ($filter = 'integer')
		{
			if (XXX_Array::hasValue(array('integer', 'float', 'hash', 'base64', 'string', 'raw'), $filter))
			{
				$this->filter = $filter;
			}
		}
		
		public function getFilter ()
		{
			return $this->filter;
		}
		
		public function setMalicious ($malicious = false)
		{
			$this->malicious = $malicious;
		}
				
	// processingState	
	
		public function setProcessingState ($processingState = '')
		{
			if ($processingState == 'blank' || $processingState == 'interacted' || $processingState == 'submitted')
			{
				$this->processingState = $processingState;
			}
		}
		
		public function getProcessingState ()
		{
			return $this->processingState;
		}
		
		public function getAttachedFormProcessingState ()
		{
			$nativeFormProcessingState = 'blank';
			
			if ($this->elements['form'])
			{
				$nativeFormProcessingState = $this->elements['form']->getProcessingState();
			}
			
			return $nativeFormProcessingState;
		}
		
		public function isProcessingStateBlank ()
		{
			$nativeFormProcessingState = $this->getAttachedFormProcessingState();
			$processingState = $this->getProcessingState();
			$isProcessingStateBlank = $nativeFormProcessingState == 'blank' && $processingState == 'blank';
			
			return $isProcessingStateBlank;
		}
		
	
		
	// composeSide
		
		public function composeClientSide ()
		{
			$this->composeSide = 'client';
		}
		
		public function composeServerSide ()
		{
			$this->composeSide = 'server';
		}
		
		public function setComposeSide ($composeSide = 'server')
		{
			if ($composeSide == 'server' || $composeSide == 'client')
			{
				$this->composeSide = $composeSide;
			}
		}
		
		public function getComposeSide ()
		{
			return $this->composeSide;
		}
		
		public function getAttachedFormComposeSide ()
		{
			$composeSide = $this->composeSide;
			
			if ($this->elements['form'])
			{
				$composeSide = $this->elements['form']->getComposeSide();
			}
			
			return $composeSide;
		}
		
	// clientSideActionProcessing
		
		public function enableClientSideActionProcessing ()
		{
			$this->clientSideActionProcessing = true;
		}
		
		public function disableClientSideActionProcessing ()
		{
			$this->clientSideActionProcessing = false;
		}
		
		public function setClientSideActionProcessing ($clientSideActionProcessing = false)
		{
			$this->clientSideActionProcessing = $clientSideActionProcessing ? true : false;
		}
		
		public function getClientSideActionProcessing ()
		{
			return $this->clientSideActionProcessing;
		}
		
		public function getAttachedFormClientSideActionProcessing ()
		{
			$clientSideActionProcessing = $this->clientSideActionProcessing;
			
			if ($this->elements['form'])
			{
				$clientSideActionProcessing = $this->elements['form']->getClientSideActionProcessing();
			}
			
			return $clientSideActionProcessing;
		}
	
	// localJSVariable
		
		public function enableLocalJSVariable ()
		{
			$this->localJSVariable = true;
		}
		
		public function disableLocalJSVariable ()
		{
			$this->localJSVariable = false;
		}
		
		public function setLocalJSVariable ($localJSVariable = false)
		{
			$this->localJSVariable = $localJSVariable ? true : false;
		}
		
		public function getLocalJSVariable ()
		{
			return $this->localJSVariable;
		}
		
		public function getAttachedFormLocalJSVariable ()
		{
			$localJSVariable = $this->localJSVariable;
			
			if ($this->elements['form'])
			{
				$localJSVariable = $this->elements['form']->getLocalJSVariable();
			}
			
			return $localJSVariable;
		}
	
	// submitted client input
	
		public function applySubmittedClientInput ()
		{
			$result = false;
			
			$inputType = $this->getInputType();
			
			if ($inputType)
			{
				switch ($inputType)
				{
					case 'button':						
						break;
					case 'characterLineInput':
					case 'maskedCharacterLineInput':
					case 'characterLinesInput':
					case 'hiddenVariableInput':
						if ($this->elements['form'])
						{
							$value = $this->elements['form']->getClientInputVariable($this->getName(), $this->getFilter());
							
							$this->setMalicious(XXX_Client_Input::isVariableMalicious($this->getName()));
							
							if (XXX_Type::isValue($value) || $value === '')
							{
								$this->setValue($value);
							}
						}
						break;
					case 'exclusiveOptionSwitchInput':
					case 'freeOptionSwitchInput':
						if ($this->elements['form'])
						{
							$option = $this->elements['form']->getClientInputVariable($this->getName(), $this->getFilter());
							$value = $this->getValue();
							
							$this->setMalicious(XXX_Client_Input::isVariableMalicious($this->getName()));
							
							if (XXX_Type::isArray($option))
							{
								if (XXX_Array::hasValue($option, $value))
								{
									$this->select();
								}
								else
								{
									$this->deselect();
								}
							}
							else
							{							
								if ($option == $this->getValue())
								{
									$this->select();
								}
								else
								{
									$this->deselect();
								}
							}
						}
						break;
					case 'exclusiveOptionListBoxInput':
					case 'exclusiveOptionSwitchListInput':
					case 'freeOptionListBoxInput':
					case 'freeOptionSwitchListInput':
						if ($this->elements['form'])
						{
							$options = $this->elements['form']->getClientInputVariable($this->getName(), $this->getFilter());
							
							$this->setMalicious(XXX_Client_Input::isVariableMalicious($this->getName()));
							
							$this->deselectAllOptions();
							
							if (XXX_Type::isArray($options))
							{
								foreach ($options as $optionValue)
								{
									$this->selectOptionByValue($optionValue);
								}
							}
							else if (XXX_Type::isValue($options))
							{
								$optionValue = $options;
								
								$this->selectOptionByValue($optionValue);
							}
						}
						break;
				}
			}
				
			return $result;
		}
	
	// variable
		
		public function getVariable ()
		{
			$result = false;
			
			$inputType = $this->getInputType();
			
			if ($inputType)
			{
				switch ($inputType)
				{
					case 'button':							
						break;
					case 'characterLineInput':
					case 'maskedCharacterLineInput':
					case 'characterLinesInput':
					case 'hiddenVariableInput':
						$result = XXX_String::trim($this->getValue());
						break;
					case 'exclusiveOptionSwitchInput':
					case 'freeOptionSwitchInput':
						$result = $this->isSelected();
						break;
					case 'exclusiveOptionListBoxInput':
					case 'exclusiveOptionSwitchListInput':
						$result = $this->getSelectedOptionValue();
						break;
					case 'freeOptionListBoxInput':
					case 'freeOptionSwitchListInput':
						$result = $this->getSelectedOptionValues();
						break;
					case 'datePickerInput':
						$result = $this->getDate();
						break;
				}
			}
			
			return $result;
		}
		
		public function setVariable ($value)
		{
			$result = false;
			
			$inputType = $this->getInputType();
			
			if ($inputType)
			{
				switch ($inputType)
				{
					case 'button':							
						break;
					case 'characterLineInput':
					case 'maskedCharacterLineInput':
					case 'characterLinesInput':
					case 'hiddenVariableInput':
						$result = $this->setValue($value);
						break;
					case 'exclusiveOptionSwitchInput':
					case 'freeOptionSwitchInput':
						$result = $this->setSelected($value);
						break;
					case 'exclusiveOptionListBoxInput':
					case 'exclusiveOptionSwitchListInput':
						$result = $this->setSelectedOptionValue($value);
						break;
					case 'freeOptionListBoxInput':
					case 'freeOptionSwitchListInput':
						$result = $this->setSelectedOptionValues($value);
						break;
					case 'datePickerInput':
						$result = $this->setDate($value);
						break;
				}
			}
			
			return $result;
		}
	
	// actions
	
		public function addAction ($actionType = 'validation', $action = '', $texts = '', $parameters = array(), $side = 'both')
		{
			if ($actionType == 'operation' || $actionType == 'validation' || $actionType == 'information' || $actionType == 'confirmation')
			{
				if ($side == 'both' || $side == 'client' || $side == 'server')
				{				
					$this->actions[$actionType][] = array
					(
						'action' => $action,
						'texts' => $texts,
						'parameters' => $parameters,
						'side' => $side
					);
				}
			}
		}
				
		public function hasActions ($type)
		{
			$count = 0;
			
			if ($type && XXX_Array::hasKey($this->actions, $type))
			{
				$count += XXX_Array::getFirstLevelItemTotal($this->actions[$type]);
			}
			else
			{			
				$count += XXX_Array::getFirstLevelItemTotal($this->actions['operation']);
				$count += XXX_Array::getFirstLevelItemTotal($this->actions['validation']);
				$count += XXX_Array::getFirstLevelItemTotal($this->actions['confirmation']);
				$count += XXX_Array::getFirstLevelItemTotal($this->actions['information']);
			}
			
			return $count;
		}
		
		public function convertActionTypeListsToFlatActionList ()
		{
			if (!$this->convertedActionTypeListsToFlatActionList)
			{
				$this->flatActionList = array();
				
				foreach ($this->actions as $actionType => $actions)
				{
					if (XXX_Array::getFirstLevelItemTotal($actions) > 0)
					{		
						for ($i = 0, $iEnd = XXX_Array::getFirstLevelItemTotal($actions); $i < $iEnd; ++$i)
						{
							$action = $actions[$i];
							
							if ($action['side'] != 'client')
							{
								$action['actionType'] = $actionType;
								
								$this->flatActionList[] = $action;
							}
						}
					}
				}
				
				$this->convertedActionTypeListsToFlatActionList = true;
			}
		}
		
		public function processActionsSub ($eventTrigger = '')
		{			
			return true;
		}
		
		public function processActions ($eventTrigger = '')
		{
			$this->convertActionTypeListsToFlatActionList();
			
			$isProcessingStateBlank = $this->isProcessingStateBlank();
			
			if (!$isProcessingStateBlank)
			{
				$this->resetFeedbackMessages();
				
				$validated = $this->processActionsSub($eventTrigger);
				
				$this->setValid($validated);				
			}
			
			if (!$isProcessingStateBlank && $eventTrigger != 'parentInput')
			{
				if ($this->elements['parentInput'])
				{					
					$this->elements['parentInput']->childProcessedActions($eventTrigger);						
				}
			}
		}
	
	// feedbackMessages
		
		public function addFeedbackMessage ($feedbackMessageType = 'validation', $feedbackMessage = '')
		{
			if ($feedbackMessageType == 'operation' || $feedbackMessageType == 'validation' || $feedbackMessageType == 'information' || $feedbackMessageType == 'confirmation')
			{
				if ($feedbackMessage != '')
				{
					$this->feedbackMessages[$feedbackMessageType][] = $feedbackMessage;
				}
			}
		}
		
		public function resetFeedbackMessages ($type)
		{
			if ($type && XXX_Array::hasKey($this->feedbackMessages, $type))
			{
				$this->feedbackMessages[$type] = array();
			}
			else
			{			
				$this->feedbackMessages = array
				(
					'operation' => array(),
					'validation' => array(),
					'information' => array(),
					'confirmation' => array()
				);
			}
		}
		
		public function hasFeedbackMessages ($type)
		{
			$count = 0;
			
			if ($type && XXX_Array::hasKey($this->feedbackMessages, $type))
			{
				$count += XXX_Array::getFirstLevelItemTotal($this->feedbackMessages[$type]);
			}
			else
			{			
				$count += XXX_Array::getFirstLevelItemTotal($this->feedbackMessages['operation']);
				$count += XXX_Array::getFirstLevelItemTotal($this->feedbackMessages['validation']);
				$count += XXX_Array::getFirstLevelItemTotal($this->feedbackMessages['confirmation']);
				$count += XXX_Array::getFirstLevelItemTotal($this->feedbackMessages['information']);
			}
			
			return $count;
		}
		
		public function propagateFeedbackMessagesUp ()
		{
			if ($this->elements['parentInput'])
			{
				for ($i = 0, $iEnd = XXX_Array::getFirstLevelItemTotal($this->feedbackMessages['operation']); $i < $iEnd; ++$i)
				{
					$this->elements['parentInput']->addFeedbackMessage('operation', $this->feedbackMessages['operation'][$i]);
				}
									
				for ($i = 0, $iEnd = XXX_Array::getFirstLevelItemTotal($this->feedbackMessages['validation']); $i < $iEnd; ++$i)
				{
					$this->elements['parentInput']->addFeedbackMessage('validation', $this->feedbackMessages['validation'][$i]);
				}
				
				for ($i = 0, $iEnd = XXX_Array::getFirstLevelItemTotal($this->feedbackMessages['confirmation']); $i < $iEnd; ++$i)
				{
					$this->elements['parentInput']->addFeedbackMessage('confirmation', $this->feedbackMessages['confirmation'][$i]);
				}
				
				for ($i = 0, $iEnd = XXX_Array::getFirstLevelItemTotal($this->feedbackMessages['information']); $i < $iEnd; ++$i)
				{
					$this->elements['parentInput']->addFeedbackMessage('information', $this->feedbackMessages['information'][$i]);
				}
			}
		}
		
	
	// other
	
		public function getInputType ()
		{
			return $this->inputType;
		}
	
	// semantic markup and content
		
	// compose
		
		public function composeFeedbackHTML ()
		{
			$html = '';
			
			$html .= $this->composeFeedbackIconHTML();
			$html .= $this->composeFeedbackMessagesHTML();
			
			return $html;
		}
		
		public function composeFeedbackIconHTML ()
		{
			$html = '';
			
			$html .= XXX_HTML_Composer::composeFeedbackIcon($this->ID . '_feedbackIcon', 'hidden');
			
			return $html;
		}
		
		public function composeFeedbackMessagesHTML ()
		{
			$html = '';
			
			$temp = XXX_HTML_Composer::composeFeedbackMessages($this->feedbackMessages);
				
			$html .= XXX_HTML_Composer::composeNativeInlineContainer($this->ID . '_feedbackMessages', $temp['html']);
			
			return $html;
		}
		
		public function composeHTML ()
		{
			return '';
		}
	
	// logic
				
		public function getJSInstanceVariable ()
		{
			return $this->ID;
		}
		
		public function composeInitializationJS ()
		{
			$js = '';
	
			$jsInstanceVariable = $this->getJSInstanceVariable();
			
			$js .= XXX_String::$lineSeparator;
			
			if ($this->getAttachedFormLocalJSVariable())
			{
				$js .= 'var ';
			}
			
			$js .= $jsInstanceVariable . ' = new ' . $this->CLASS_NAME . '(\'' . $this->ID;
			
			if ($this->ID != $this->name)
			{
				$js .= '\', \'' . $this->name;
			}
			
			$js .= '\');' . XXX_String::$lineSeparator;
					
			if ($this->elements['form'])
			{
				$js .= $jsInstanceVariable . '.attachForm(' . $this->elements['form']->getJSInstanceVariable() . ');' . XXX_String::$lineSeparator;	
			}
					
			if ($this->elements['parentInput'])
			{
				$js .= $jsInstanceVariable . '.attachParentInput(' . $this->elements['parentInput']->getJSInstanceVariable() . ');' . XXX_String::$lineSeparator;	
			}
									
			if (XXX_Type::isFilledArray($this->presentation))
			{
				if (!(XXX_Array::getFirstLevelItemTotal($this->presentation) == 3 && $this->presentation['confirmation'] && $this->presentation['feedbackIcon'] && $this->presentation['showFeedbackMessagesAlwaysAsBlock']))
				{
					$js .= $jsInstanceVariable . '.setPresentation(' . XXX_String_JSON::encode($this->presentation) . ');' . XXX_String::$lineSeparator;
				}
			}
			
			return $js;
		}
		
		public function composeJS ()
		{
			$js = '';
			
			$jsInstanceVariable = $this->getJSInstanceVariable();
						
			if ($this->processingState != 'blank')
			{
				$js .= $jsInstanceVariable . '.setProcessingState(\'' . $this->processingState . '\');' . XXX_String::$lineSeparator;
			}
			
			// Actions
				
				foreach ($this->actions as $actionType => $actions)
				{
					if (XXX_Array::getFirstLevelItemTotal($actions) > 0)
					{
						foreach ($actions as $action)
						{
							if ($action['side'] != 'server')
							{
								$js .= $jsInstanceVariable . '.addAction(\'' . $actionType . '\', \'' . $action['action'] . '\', ' . XXX_String_JSON::encode($action['texts']);
								
								$js .= ', ' . (XXX_Type::isEmptyArray($action['parameters']) ? '{}' : XXX_String_JSON::encode($action['parameters']));
								
								if ($action['side'] != 'both')
								{
									 $js .= ', \'' . $action['side'] . '\'';
								} 
								
								$js .= ');' . XXX_String::$lineSeparator;
							}
						}
					}
				}
				
			// FeedbackMessages
				
				foreach ($this->feedbackMessages as $feedbackMessageType => $feedbackMessages)
				{
					if (XXX_Array::getFirstLevelItemTotal($feedbackMessages) > 0)
					{
						foreach ($feedbackMessages as $feedbackMessage)
						{
							$js .= $jsInstanceVariable . '.addFeedbackMessage(\'' . $feedbackMessageType . '\', \'' . XXX_String::addSlashes($feedbackMessage) . '\');' . XXX_String::$lineSeparator;
						}
					}
				}
			
			if (!$this->getAttachedFormClientSideActionProcessing())
			{
				$js .= $jsInstanceVariable . '.setClientSideActionProcessing(false);' . XXX_String::$lineSeparator;
			}
			
			switch ($this->getAttachedFormComposeSide())
			{
				case 'server':
					$js .= $jsInstanceVariable . '.parseElements();' . XXX_String::$lineSeparator;
					break;
				case 'client':
					$js .= $jsInstanceVariable . '.composeElements(\'' . $this->ID . '_container\');' . XXX_String::$lineSeparator;
					break;
			}
		
			return $js;
		}
	
		public function composeElementsReadyJS ()
		{
			return '';
		}
		
		public function composeAsynchronousResponse ()
		{
			return array
			(
				'ID' => $this->ID
			);
		}
}


?>