<?php

class XXX_Component_FileUploadListInput extends XXX_Component_Input
{
	public $CLASS_NAME = 'XXX_Component_FileUploadListInput';
	
	protected $inputType = 'fileUploadInputListInput';
	
	protected $slots = 3;
	
	public function __construct ($ID = '', $name = '')
	{
		parent::__construct($ID, $name);
		
		$this->setPresentation(array(
			'columns' => 1
		));
		
		$this->elements['fileUploadInputs'] = array();
	}
	
	public function setPresentation (array $presentation = array())
	{
		parent::setPresentation($presentation);
						
		$this->presentation['columns'] = XXX_Default::toPositiveInteger($this->presentation['columns'], 1);
	}
	
	public function setSlots ($slots = 1)
	{
		if (XXX_Type::isPositiveInteger($slots) && $slots > 0)
		{
			$this->slots = $slots;
		}
	}
	
	protected function createFileUploadInputs ()
	{
		for ($i = 0, $iEnd = $this->slots; $i < $iEnd; ++$i)
		{
			$fileUploadInput = new XXX_Component_FileUploadInput($this->ID . '_' . $i, $this->name);
			$fileUploadInput->attachForm($this->elements['form']);
			$fileUploadInput->setSubmitOnFileSelection(false);
			$fileUploadInput->setEditable($this->isEditable());
			$fileUploadInput->setDisabled($this->isDisabled());
			
			$this->elements['fileUploadInputs'][] = $fileUploadInput;
		}
	}
	
	public function composeHTML ()
	{
		$this->createFileUploadInputs();
		
		$html = '';
		
		switch ($this->getAttachedFormComposeSide())
		{
			case 'server':
				$composedSlotCountManipulator = XXX_HTML_Composer::composeSlotCountManipulator($this->ID . '_slotCountManipulator');
				
				$composedFileUploadInputs = array();
				
				for ($i = 0, $iEnd = XXX_Array::getFirstLevelItemTotal($this->elements['fileUploadInputs']); $i < $iEnd; ++$i)
				{
					$composedFileUploadInputs[] = $this->elements['fileUploadInputs'][$i]->composeHTML();
				}
									
				$html .= XXX_HTML_Composer::composeBasicGrid($this->ID . '_basicGrid', $composedFileUploadInputs, $this->presentation['columns']);
				
				$html .= $composedSlotCountManipulator;
				
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
		
		if ($this->slots != 3)
		{
			$js .= $jsInstanceVariable . '.setSlots(' . $this->slots . ');' . XXX_String::$lineSeparator;
		}
		
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
					
		return $js;
	}
}


?>