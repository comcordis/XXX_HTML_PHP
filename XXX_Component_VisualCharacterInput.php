<?php


abstract class XXX_Component_VisualCharacterInput extends XXX_Component_CharacterInput
{
	public $CLASS_NAME = 'XXX_Component_VisualCharacterInput';
	
	protected $inputType = 'visualCharacterInput';
	
	protected $exampleValue = '';	
	protected $elasticity = false;	
	protected $selectAllOnFocus = false;	
	protected $focusOnLoad = false;	
	protected $jumpFromPreviousInput = false;
		
	public function __construct ($ID = '', $name = '')
	{
		parent::__construct($ID, $name);
	}
	
	public function setExampleValue ($exampleValue)
	{
		$this->exampleValue = $exampleValue;
	}
	
	public function getValue ()
	{
		$value = parent::getValue();
		
		if ($value == $this->exampleValue)
		{
			$value = '';
		}
		
		return $value;
	}
	
	// Misc.
	
		public function getAttachedFormBrowserSpellCheck ()
		{
			$formBrowserSpellCheck = false;
			
			if ($this->elements['form'])
			{
				$formBrowserSpellCheck = $this->elements['form']->getBrowserSpellCheck();
			}
			
			return $formBrowserSpellCheck;
		}
		
		public function getAttachedFormBrowserAutoComplete ()
		{
			$formBrowserAutoComplete = false;
			
			if ($this->elements['form'])
			{
				$formBrowserAutoComplete = $this->elements['form']->getBrowserAutoComplete();
			}
			
			return $formBrowserAutoComplete;
		}
		
	public function makeElastic ($minimum, $maximum)
	{
		if (XXX_Type::isPositiveInteger($minimum) && XXX_Type::isPositiveInteger($maximum) && $maximum >= $minimum)
		{			
			$this->elasticity = array
			(
				'minimum' => $minimum,
				'maximum' => $maximum
			);
		}
	}
	
	public function selectAllOnFocus ()
	{
		$this->selectAllOnFocus = true;
	}
	
	public function focusOnLoad ()
	{
		$this->focusOnLoad = true;
	}
	
	public function focusAfterOtherInputReachesCharacterLength ($otherInput, $characterLength)
	{
		$this->focusAfterOtherInputReachesCharacterLength = array
		(
			'otherInput' => $otherInput,
			'characterLength' => $characterLength
		);
	}
		
	public static function composeJSString ($string = '')
	{
		$result = '"';
						
		$string = XXX_String::addSlashes($string);
		$string = XXX_String::replace($string, "\r", '\r');
		$string = XXX_String::replace($string, "\n", '\n');
		
		$result .= $string;
		
		$result .= '"';
		
		return $result;
	}
	
	public function composeElementsReadyJS ()
	{
		$js = '';
		
		$jsInstanceVariable = $this->getJSInstanceVariable();
		
		if ($this->elasticity)
		{
			$js .= $jsInstanceVariable . '.makeElastic(' . $this->elasticity['minimum'] . ', ' . $this->elasticity['maximum'] . ');' . XXX_String::$lineSeparator;
		}
		
		if ($this->selectAllOnFocus)
		{
			$js .= $jsInstanceVariable . '.selectAllOnFocus();' . XXX_String::$lineSeparator;
		}
		
		if ($this->focusOnLoad)
		{
			$js .= $jsInstanceVariable . '.focusOnLoad();' . XXX_String::$lineSeparator;
		}
		
		if ($this->focusAfterOtherInputReachesCharacterLength)
		{
			$js .= $jsInstanceVariable . '.focusAfterOtherInputReachesCharacterLength(' . $this->focusAfterOtherInputReachesCharacterLength['otherInput']->getJSInstanceVariable() . ', ' . $this->focusAfterOtherInputReachesCharacterLength['characterLength'] . ');' . XXX_String::$lineSeparator;
		}
		
		return $js;
	}
	
	public function composeJS ()
	{
		$js = '';
	
		$jsInstanceVariable = $this->getJSInstanceVariable();
								
		if (XXX_Type::isValue($this->exampleValue))
		{
			$js .= $jsInstanceVariable . '.setExampleValue(' . XXX_Component_VisualCharacterInput::composeJSString($this->exampleValue) . ');' . XXX_String::$lineSeparator;
		}
		
		$js .= parent::composeJS();
		
		return $js;
	}
}


?>