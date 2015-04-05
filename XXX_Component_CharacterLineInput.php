<?php


class XXX_Component_CharacterLineInput extends XXX_Component_VisualCharacterInput
{
	public $CLASS_NAME = 'XXX_Component_CharacterLineInput';
	
	protected $inputType = 'characterLineInput';
					
	public function __construct ($ID = '', $name = '')
	{
		parent::__construct($ID, $name);
		
		$this->setPresentation(array(
			'characterDisplay' => 'plain',
			'lineCharacterLength' => 32,
			'align' => 'left'
		));
	}
	
	public function setPresentation (array $presentation = array())
	{
		parent::setPresentation($presentation);
		
		$this->presentation['characterDisplay'] = XXX_Default::toOption($this->presentation['characterDisplay'], array('plain', 'masked'), 'plain');
		$this->presentation['lineCharacterLength'] = XXX_Default::toPositiveInteger($this->presentation['lineCharacterLength'], 1);	
		$this->presentation['align'] = XXX_Default::toOption($this->presentation['align'], array('left', 'center', 'right'), 'left');
	}
	
	public function setAlign ($align = 'left')
	{
		if ($align == 'left' || $align == 'center' || $align == 'right')
		{
			$this->presentation['align'] = $align;
		}
	}
	
	public function getLineCharacterLength ()
	{
		$lineCharacterLength = $this->presentation['lineCharacterLength'];
					
		if ($this->elasticity)
		{
			$temp = $this->getValue();
			
			if ($temp == '' && $this->exampleValue != '')
			{
				$temp = $this->exampleValue;
			}
		
			$valueCharacterLength = XXX_String::getCharacterLength($temp);
			
			if ($valueCharacterLength < $this->elasticity['minimum'])
			{
				$lineCharacterLength = $this->elasticity['minimum'];
			}
			else if ($valueCharacterLength > $this->elasticity['maximum'])
			{
				$lineCharacterLength = $this->elasticity['maximum'];
			}
			else
			{
				$lineCharacterLength = $valueCharacterLength;
			}
		}
		
		return $lineCharacterLength;
	}
		
	public function composeHTML ()
	{
		$html = '';
		
		switch ($this->getAttachedFormComposeSide())
		{
			case 'server':
					$lineCharacterLength = $this->getLineCharacterLength();
								
					$tempAttributes = array
					(
						array('key' => 'ID', 'value' => $this->ID . '_nativeCharacterLineInput'),
						array('key' => 'name', 'value' => $this->name),
						array('key' => 'characterDisplay', 'value' => $this->presentation['characterDisplay']),
						array('key' => 'lineCharacterLength', 'value' => $lineCharacterLength),
						array('key' => 'style', 'value' => ('text-align: ' . $this->presentation['align'] . ';')),
						array('key' => 'browserSpellCheck', 'value' => $this->getAttachedFormBrowserSpellCheck()),
						array('key' => 'browserAutoComplete', 'value' => $this->getAttachedFormBrowserAutoComplete()),
						array('key' => 'readOnly', 'value' => !$this->isEditable()),
						array('key' => 'disabled', 'value' => $this->isDisabled())
					);
					
				$html .= XXX_HTML_Composer::composeNativeCharacterLineInput($tempAttributes, $this->getValue());
				
				if ($this->presentation['inputField'] == 'self')
				{
					$html .= $this->composeFeedbackIconHTML();
					
					$html = XXX_HTML_Composer::composeInputField($this->ID . '_inputField', $html, 'camoflaged', $this->presentation['inputFieldIcon']);
					
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
				
		$js .= $jsInstanceVariable . '.setValue(' . XXX_Composer_JS::composeString($this->getValue()) . ');' . XXX_String::$lineSeparator;
				
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
			'lineCharacterLength' => $this->getLineCharacterLength(),
			'value' => $this->getValue(),
			'disabled' => $this->isDisabled(),
			'editable' => $this->isEditable(),
			'valid' => $this->isValid(),
			'feedbackMessages' => $this->feedbackMessages
		);
		
		return $asynchronousResponse;
	}
	
}


?>