<?php



class XXX_Component_CharacterLinesInput extends XXX_Component_VisualCharacterInput
{
	public $CLASS_NAME = 'XXX_Component_CharacterLinesInput';
	
	protected $inputType = 'characterLinesInput';
	
	public function __construct ($ID = '', $name = '')
	{
		parent::__construct($ID, $name);
		
		$this->setPresentation(array(
			'lineCharacterLength' => 32,
			'lines' => 3
		));
	}
	
	public function setPresentation (array $presentation = array())
	{
		parent::setPresentation($presentation);
						
		$this->presentation['lineCharacterLength'] = XXX_Default::toPositiveInteger($this->presentation['lineCharacterLength'], 1);					
		$this->presentation['lines'] = XXX_Default::toPositiveInteger($this->presentation['lines'], 1);			
	}
	
	public function getLineCharacterLength ()
	{
		return $this->presentation['lineCharacterLength'];
	}
	
	public function getLines ()
	{
		return $this->presentation['lines'];
	}
	
	public function composeHTML ()
	{
		$html = '';
		
		switch ($this->getAttachedFormComposeSide())
		{
			case 'server':
					$tempAttributes = array
					(
						array('key' => 'ID', 'value' => $this->ID . '_nativeCharacterLinesInput'),
						array('key' => 'name', 'value' => $this->name),						
						array('key' => 'lineCharacterLength', 'value' => $this->presentation['lineCharacterLength']),
						array('key' => 'lines', 'value' => $this->presentation['lines']),
						array('key' => 'browserSpellCheck', 'value' => $this->getAttachedFormBrowserSpellCheck()),
						array('key' => 'browserAutoComplete', 'value' => $this->getAttachedFormBrowserAutoComplete()),
						array('key' => 'readOnly', 'value' => !$this->isEditable()),
						array('key' => 'disabled', 'value' => $this->isDisabled())
					);
					
				$html .= XXX_HTML_Composer::composeNativeCharacterLinesInput($tempAttributes, $this->getValue());
								
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
			'lines' => $this->getLines(),
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