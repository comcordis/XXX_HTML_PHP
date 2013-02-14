<?php

class XXX_Component_FileUploadInput extends XXX_Component_Input
{
	public $CLASS_NAME = 'XXX_Component_FileUploadInput';
	
	protected $inputType = 'fileUploadInput';
	
	protected $submitOnFileSelection = true;
			
	public function __construct ($ID = '', $name = '')
	{
		parent::__construct($ID, $name);
		
		if ($nativeForm)
		{
			$nativeForm->enableFileUpload();
		}		
	}
	
	public function setSubmitOnFileSelection ($submitFileOnSelection = true)
	{
		$this->submitOnFileSelection = $submitOnFileSelection ? true : false;
	}
								
	public function composeHTML ()
	{
		$html = '';
		
		switch ($this->getAttachedFormComposeSide())
		{
			case 'server':
					$composedNativeFileUploadInput = XXX_HTML_Composer::composeNativeFileUploadInput(array
					(
						array('key' => 'ID', 'value' => $this->ID . '_nativeFileUploadInput'),
						array('key' => 'name', 'value' => 'Filedata[]'),
						array('key' => 'acceptFileMIMETypes', 'value' => $this->elements['form']->getHTTPServer_Client_Input_LimitsProfileAcceptFileMIMETypes()),
						array('key' => 'readOnly', 'value' => !$this->isEditable()),
						array('key' => 'disabled', 'value' => $this->isDisabled())
					), 1);
				
				$html .= $composedNativeFileUploadInput;
				
				$html .= $this->composeFeedbackHTML();
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
		
		if (!$this->submitOnFileSelection)
		{
			$js .= $jsInstanceVariable . '.setSubmitOnFileSelection(false);' . XXX_String::$lineSeparator;
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