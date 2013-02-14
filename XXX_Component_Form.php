<?php

// TODO honeypot field, "username" or something, if filled in a bot might be used. Watch out with autofill. Instead use a checkbox "Check this if you're a computer" and hide it with css.
// One of the tips from the original post was to use a “honeypot field” — a hidden CSS field invisible to humans, but fed to bots. Anton’s comment described a solution that uses 3 buttons with fake input element labels. For example:  “Don’t post this” | “Post this” | “Cancel”You can catch robots with server-side validation easily because they tend to just “submit.”
// TODO time humanly possible vs time a bot would take to fill it out.
// TODO add a field with mouse/keyboard interactions
// TODO extra step trick
// http://snook.ca/archives/other/effective_blog_comment_spam_blocker

class XXX_Component_Form
{
	public $CLASS_NAME = 'XXX_Component_Form';
	
	public static $instanceCounter = 0;
	
	public $ID = 0;
	public $name = '';
	
	public $processingState = 'blank';
	public $inputsWithValidationCompletionProgress = 0;
	public $valid = true;
	
	// A day
	protected $maximumTokenTimestampInterval = 86400;
	
	protected $submitURI = '';
	protected $submitAsynchronous = false;
	
	protected $fileUpload = false;	
	protected $HTTPServer_Client_Input_LimitsProfile = 'default';
	
	protected $acceptCharacterSet = 'utf-8';
	
	protected $browserSpellCheck = false;
	protected $browserAutoComplete = false;
	
	protected $transportMethod = 'body';
	protected $transportFormat = 'multipart/form-data';
	
	protected $tokenSecurity = false;
	protected $token = '';
	protected $tokenTimestamp = 0;
	
	protected $elements = array
	(
		'inputs' => array()
	);
	
	protected $inputs = array();
			
	protected $composeSide = 'server';
	protected $clientSideActionProcessing = true;
	protected $localJSVariable = false;
	protected $completionProgress = false;
	
	public function __construct ($ID = '', $name = '')
	{
		if (!XXX_Type::isValue($ID))
		{
			$ID = 'XXX_Component_Form_' . ++self::$instanceCounter;
		}
		
		if (!XXX_Type::isValue($name))
		{
			$name = $ID;
		}
		
		$this->ID = $ID;
		$this->name = $name;
				
		$this->elements['fileUploadManager'] = new XXX_Component_FileUploadManager($this->ID . '_fileUploadManager');
	}
	
	// ID
	
		public function setID ($ID = '')
		{
			$this->ID = $ID;
		}
		
		public function getID ()
		{
			return $this->ID;
		}
	
	// name
		
		public function setName ($name = '')
		{
			$this->name = $name;
		}
		
		public function getName ()
		{
			return $this->name;
		}
	
	// processingState
	
		public function setProcessingState ($processingState = '', $propagateDirection = '')
		{
			if ($processingState == 'blank' || $processingState == 'interacted' || $processingState == 'submitted')
			{
				$this->processingState = $processingState;
				
				switch ($propagateDirection)
				{
					case 'down':
						foreach ($this->elements['inputs'] as $input)
						{
							$input->setProcessingState($processingState);
						}
						break;
				}
			}
		}
		
		public function getProcessingState ()
		{
			return $this->processingState;
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
	
	// showCompletionProgress
		
		public function showCompletionProgress ()
		{
			$this->completionProgress = true;
		}
		
		public function hideCompletionProgress ()
		{
			$this->completionProgress = true;
		}
	
	// submit
	
		public function setSubmitURI ($submitURI = '')
		{
			$this->submitURI = $submitURI;
		}
		
		public function setSubmitAsynchronous ($submitAsynchronous)
		{		
			$this->submitAsynchronous = $submitAsynchronous ? true : false;
		}
	
	// completed
		
		public function isSubmittableToApplication ()
		{
			return $this->isSubmitted() && $this->isValid();
		}
		
	// transport
		
		public function setTransportMethod ($transportMethod = 'body')
		{
			if ($transportMethod == 'body' || $transportMethod == 'uri')
			{
				if ($transportMethod == 'uri' && !$this->fileUpload)
				{
					$this->transportMethod = 'uri';
				}
				else
				{
					$this->transportMethod = 'body';
				}
			}
		}	
	
		protected function correctTransport ()
		{
			if ($this->fileUpload)
			{
				$this->transportMethod = 'body';
				$this->transportFormat = 'multipart/form-data';
			}
			else
			{
				if ($this->transportMethod == 'uri')
				{
					$this->transportFormat = 'application/x-www-form-urlencoded';
				}
				else
				{
					// PHP doesn't parse text/plain... only raw
					$this->transportFormat = 'multipart/form-data';
				}
			}
		}
	
	// file upload
		
		public function enableFileUpload ()
		{
			if (!$this->fileUpload)
			{
				$this->fileUpload = true;
				
				$this->hideCompletionProgress();
			}
		}
		
		public function setHTTPServer_Client_Input_LimitsProfile ($HTTPServer_Client_Input_LimitsProfile)
		{
			if (XXX_Array::hasKey(XXX_HTTPServer_Client_Input::$profiles, $HTTPServer_Client_Input_LimitsProfile))
			{
				$this->HTTPServer_Client_Input_LimitsProfile = $HTTPServer_Client_Input_LimitsProfile;
			}
		}
		
		public function getHTTPServer_Client_Input_LimitsProfileMaximumFileSize ()
		{
			return XXX_HTTPServer_Client_Input::$profiles[$this->HTTPServer_Client_Input_LimitsProfile]['maximumFileSize'];
		}
		
		public function getHTTPServer_Client_Input_LimitsProfileAcceptFileMIMETypes ()
		{
			return XXX_HTTPServer_Client_Input::$profiles[$this->HTTPServer_Client_Input_LimitsProfile]['acceptFileMIMETypes'];
		}
				
	// Misc. properties
		
		public function setAcceptCharacterSet ($acceptCharacterSet = 'utf-8')
		{
			$this->acceptCharacterSet = $acceptCharacterSet;
		}
		
		public function setBrowserSpellCheck ($browserSpellCheck = false)
		{
			$this->browserSpellCheck = $browserSpellCheck ? true : false;		
		}
		
		public function getBrowserSpellCheck ()
		{
			return $this->browserSpellCheck;
		}
		
		public function setBrowserAutoComplete ($browserAutoComplete = false)
		{
			$this->browserAutoComplete = $browserAutoComplete ? true : false;		
		}
		
		public function getBrowserAutoComplete ()
		{
			return $this->browserAutoComplete;
		}
	
	// State
		
		public function setValid ($valid = false)
		{
			$this->valid = $valid ? true : false;
		}
		
		public function isValid ()
		{
			return $this->valid;
		}		
		
		public function isSubmitted ()
		{
			return ($this->processingState == 'submitted');
		}
		
	// tokenSecurity
		
		public function setTokenSecurity ($tokenSecurity = false)
		{
			$this->tokenSecurity = $tokenSecurity ? true : false;
		}
		
		public function enableTokenSecurity ()
		{
			$this->tokenSecurity = true;
		}
		
		public function disableTokenSecurity ()
		{
			$this->tokenSecurity = false;
		}
	
	
	public function attachInput ($input)
	{
		if ($input)
		{
			$this->elements['inputs'][] = $input;	
		}
	}
	
	
	public function getClientInputVariable ($key, $filter = 'integer')
	{
		$value = '';
		
		switch ($this->transportMethod)
		{
			case 'body':
				$value = XXX_HTTPServer_Client_Input::getBodyVariable($key, $filter);
				break;
			case 'uri':
				$value = XXX_HTTPServer_Client_Input::getURIVariable($key, $filter);
				break;
		}
		
		return $value;
	}
	
	protected function applySubmittedClientInput ()
	{
		// File upload
		
			$fileUploadResponse = XXX_HTTPServer_Client_Input::getNativeHTMLFileUploads();
			
			if (XXX_Type::isArray($fileUploadResponse))
			{
				$this->elements['fileUploadManager']->processFileUploads($fileUploadResponse);
			}
		
		// Variables
		
			foreach ($this->elements['inputs'] as $input)
			{
				$input->applySubmittedClientInput();
			}
	}
	
	// Process all 
		
		public function processAllInputActions ($eventTrigger = '')
		{
			foreach ($this->elements['inputs'] as $input)
			{
				$input->processActions($eventTrigger);
			}
		}
		
		public function checkIfAllInputsAreValid ($eventTrigger = '')
		{
			$valid = true;
			
			$invalidInputsWithValidationTotal = 0;
			$inputsWithValidationTotal = 0;
			
			foreach ($this->elements['inputs'] as $input)
			{
				if (!$input->isValid())
				{
					if ($eventTrigger == 'submitForm' || $input->getProcessingState() != 'blank')
					{
						$valid = false;
					}
				}
					
				if ($input->hasActions('validation') && !$input->elements['parentInput'])
				{
					++$inputsWithValidationTotal;
					
					if (!$input->isValid() || $input->getProcessingState() == 'blank')
					{
						++$invalidInputsWithValidationTotal;
					}
				}
			}
			
			$this->inputsWithValidationCompletionProgress = 1;
			
			if ($inputsWithValidationTotal > 0)
			{
				$this->inputsWithValidationCompletionProgress = 1 - ($invalidInputsWithValidationTotal / $inputsWithValidationTotal);
			}
			
			return $valid;
		}
		
		public function potentialInputChange ()
		{
			$valid = $this->checkIfAllInputsAreValid('potentialInputChange');
						
			if (!$valid)
			{
				$this->disableSubmitFormButtons('invalidFormInputs');
			}
			else
			{
				$this->enableSubmitFormButtons();
			}
		}
		
		public function process ()
		{
			$this->correctTransport();
			
			if (!XXX_HTTPServer_Client_Input::$withinRequestBodyLimits)
			{
				XXX_Debug::debugNotification(array($this->CLASS_NAME, 'process'), XXX_I18n_Translation::get('HTTPServer_Client_Input', 'errors', 'exceedsPostMaxSizeOrMaxInputTimeServerDirective'));
			}
			
			$submitted = $this->getClientInputVariable($this->name . '_submitted', 'string') == 'submitted';
			
			if ($submitted)
			{
				$tokenValid = false;
				
				if ($this->tokenSecurity)
				{
					$submittedToken = $this->getClientInputVariable($this->name . '_token', 'string');
					$submittedTokenTimestamp = $this->getClientInputVariable($this->name . '_tokenTimestamp', 'integer');
					
					$sessionToken = XXX_HTTP_Cookie_Session::getVariable($this->name . '_token');
					$sessionTokenTimestamp = XXX_HTTP_Cookie_Session::getVariable($this->name . '_tokenTimestamp');
					
					$tokenTimestampInterval = XXX_TimestampHelpers::getCurrentTimestamp() - $sessionTokenTimestamp;
					
					if ($tokenTimestampInterval <= $this->maximumTokenTimestampInterval)
					{
						if ($submittedToken == $sessionToken && $submittedTokenTimestamp == $sessionTokenTimestamp)
						{
							$tokenValid = true;
						}
						else
						{
							XXX_Debug::debugNotification(array($this->CLASS_NAME, 'process'), 'Token invalid in form "' . $this->name . '".');
							
							// TODO ip userAgentString (now implicitly via session)
							XXX::dispatchEventToListeners('invalidFormToken', array('formName' => $this->name));
						}
					}
					else
					{
						XXX_Debug::debugNotification(array($this->CLASS_NAME, 'process'), 'Token expired in form "' . $this->name . '".');
					}
				}
				
				if (!$this->tokenSecurity || $tokenValid)
				{
					$this->setProcessingState('submitted', 'down');
				
					$this->applySubmittedClientInput();
					
					$this->processAllInputActions('submitForm');
										
					$valid = $this->checkIfAllInputsAreValid('submitForm');	
					
					if ($valid)
					{
						if ($this->tokenSecurity && !$tokenValid)
						{
							$valid = false;
						}
					}
					
					$this->valid = $valid;
				}
				else
				{
					$this->processingState = 'blank';
				}
			}
			else
			{
				$this->processingState = 'blank';
			}
			
			if ($this->tokenSecurity)
			{
				// TODO make tokens in the dataBase with userAgent IP etc. timestamp, and form ID/name, invalidate previous one if new one is requested based on form ID/name etc.
				if ($this->getProcessingState() == 'submitted' && $this->valid)
				{
					XXX_HTTP_Cookie_Session::deleteVariable($this->name . '_token');
					XXX_HTTP_Cookie_Session::deleteVariable($this->name . '_tokenTimestamp');
										
					$this->token = '';
					$this->tokenTimestamp = 0;
				}
				else
				{
					$token = XXX_String::getRandomHash();
					$tokenTimestamp = XXX_TimestampHelpers::getCurrentTimestamp();
					
					XXX_HTTP_Cookie_Session::setVariable($this->name . '_token', $token);
					XXX_HTTP_Cookie_Session::setVariable($this->name . '_tokenTimestamp', $tokenTimestamp);
					
					$this->token = $token;
					$this->tokenTimestamp = $tokenTimestamp;
				}
			}
		}
	
	// variables
	
		public function getVariables ()
		{
			$variables = array();
			
			foreach ($this->elements['inputs'] as $input)
			{
				$name = $input->getID();
				
				$prefix = $this->getID() . '_';
				
				if (XXX_String::beginsWith($name, $prefix))
				{
					$prefixCharacterLength = XXX_String::getCharacterLength($prefix);
					
					$name = XXX_String::getPart($name, $prefixCharacterLength);
				}
				
				$variables[$name] = $input->getVariable();
			}
			
			return $variables;
		}
		
		public function setVariables ($variables)
		{
			$result = false;
			
			foreach ($this->elements['inputs'] as $input)
			{
				$name = $input->getID();
				
				$prefix = $this->getID() . '_';
				
				if (XXX_String::beginsWith($name, $prefix))
				{
					$prefixCharacterLength = XXX_String::getCharacterLength($prefix);
					
					$name = XXX_String::getPart($name, $prefixCharacterLength);
				}
				
				if (XXX_Array::hasKey($variables, $name))
				{
					$input->setVariable($variables[$name]);
				}
				
				$result = true;
			}
			
			return $result;
		}
	
	// content
	
		public function composeHTML ($composedContent = '')
		{
			$html = '';
						
			switch ($this->composeSide)
			{
				case 'server':
					
					$this->correctTransport();
										
					$nativeFormAttributes = array
					(
						array('key' => 'ID', 'value' => $this->ID . '_nativeForm'),
						array('key' => 'name', 'value' => $this->name),
						array('key' => 'acceptCharacterSet', 'value' => $this->acceptCharacterSet),
						array('key' => 'browserSpellCheck', 'value' => $this->browserSpellCheck),
						array('key' => 'browserAutoComplete', 'value' => $this->browserAutoComplete),
						array('key' => 'transportMethod', 'value' => $this->transportMethod),
						array('key' => 'transportFormat', 'value' => $this->transportFormat),
						array('key' => 'submitURI', 'value' => $this->submitURI)
					);
					
					if ($this->fileUpload)
					{
						$nativeFormAttributes[] = array('key' => 'acceptFileMIMETypes', 'value' => $this->getHTTPServer_Client_Input_LimitsProfileAcceptFileMIMETypes());
					}
					
					$extraComposedContent = '';
					
					if ($this->submitAsynchronous)
					{
							$nativeInlineFrameID = $this->ID . '_nativeAsynchronousSubmitInlineFrameInput';
							
							$nativeInlineFrameAttributes = array
							(
								array('key' => 'ID', 'value' => $nativeInlineFrameID),
								array('key' => 'name', 'value' => $nativeInlineFrameID),
								array('key' => 'class', 'value' => 'XXX_AsynchronousSubmitInlineFrameInput')
							);
				
						$extraComposedContent .= XXX_HTML_Composer::composeNativeInlineFrame($nativeInlineFrameAttributes, '');
					}
					
					// File upload
					
						if ($this->fileUpload)
						{				
							$extraComposedContent .= XXX_HTML_Composer::composeNativeBlockContainer($this->ID . '_fileUploadManager');		
						}
					
					// Token
						if ($this->tokenSecurity)
						{	
							$tokenAttributes = array
							(
								array('key' => 'name', 'value' => $this->name . '_token')
							);
							
							$extraComposedContent .= XXX_HTML_Composer::composeNativeHiddenVariableInput($tokenAttributes, $this->token);
							
							$tokenTimestampAttributes = array
							(
								array('key' => 'name', 'value' => $this->name . '_tokenTimestamp')
							);
							
							$extraComposedContent .= XXX_HTML_Composer::composeNativeHiddenVariableInput($tokenTimestampAttributes, $this->tokenTimestamp);
						}
					
					// Submitted
								
						$tempAttributes = array
						(
							array('key' => 'name', 'value' => $this->name . '_submitted')
						);
						
						$extraComposedContent .= XXX_HTML_Composer::composeNativeHiddenVariableInput($tempAttributes, 'submitted');
					
					if ($this->completionProgress)
					{
						$extraComposedContent .= XXX_HTML_Composer::composeCompletionProgress($this->ID);
					}
					
					$composedContent = $extraComposedContent . $composedContent;
										
					$composedNativeForm = XXX_HTML_Composer::composeNativeForm($nativeFormAttributes, $composedContent);
					
					$html .= $composedNativeForm;
					break;
				case 'client':
					$html .= XXX_HTML_Composer::composeNativeBlockContainer($this->ID . '_container', $composedContent);
					break;
			}
			
			return $html;
		}
	
	// logic 
	
		public function getJSInstanceVariable ()
		{
			return $this->ID;
		}
		
		public function composeJS ()
		{
			$js = '';
		
			$jsInstanceVariable = $this->getJSInstanceVariable();
			
			$js .= XXX_String::$lineSeparator;
			
			if ($this->localJSVariable)
			{
				$js .= 'var ';
			}
			
			$js .= $jsInstanceVariable . ' = new XXX_Component_Form(\'' . $this->ID . '\', \'' . $this->name . '\');' . XXX_String::$lineSeparator;
			
			if ($this->processingState != 'blank')
			{
				$js .= $jsInstanceVariable . '.setProcessingState(\'' . $this->processingState . '\');' . XXX_String::$lineSeparator;
			}
			
			if ($this->transportMethod != 'body')
			{
				$js .= $jsInstanceVariable . '.setTransportMethod(\'' . $this->transportMethod . '\');' . XXX_String::$lineSeparator;
			}
			
			if ($this->fileUpload)
			{
				$js .= $jsInstanceVariable . '.setFileUpload(true);' . XXX_String::$lineSeparator;				
				$js .= $jsInstanceVariable . '.setHTTPServer_Client_Input_LimitsProfile(\'' . $this->HTTPServer_Client_Input_LimitsProfile . '\');' . XXX_String::$lineSeparator;
			}
			
			if ($this->completionProgress)
			{
				$js .= $jsInstanceVariable . '.showCompletionProgress();' . XXX_String::$lineSeparator;
			}
			
			if ($this->tokenSecurity)
			{
				$js .= $jsInstanceVariable . '.enableTokenSecurity();' . XXX_String::$lineSeparator;
			}
						
			if ($this->browserSpellCheck)
			{
				$js .= $jsInstanceVariable . '.setBrowserSpellCheck(true);' . XXX_String::$lineSeparator;
			}
			
			if ($this->browserAutoComplete)
			{
				$js .= $jsInstanceVariable . '.setBrowserAutoComplete(true);' . XXX_String::$lineSeparator;
			}
			
			if ($this->submitURI != '')
			{
				$js .= $jsInstanceVariable . '.setSubmitURI(' . XXX_String_JSON::encode($this->submitURI) . ');' . XXX_String::$lineSeparator;
			}
			
			if ($this->submitAsynchronous)
			{
				$js .= $jsInstanceVariable . '.setSubmitAsynchronous(true);' . XXX_String::$lineSeparator;
			}
			
			if (!$this->clientSideActionProcessing)
			{
				$js .= $jsInstanceVariable . '.setClientSideActionProcessing(false);' . XXX_String::$lineSeparator;
			}
			
			switch ($this->composeSide)
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
		
		
		
	public function composeAsynchronousResponse ()
	{
		$asynchronousResponse = array
		(
			'ID' => $this->ID,
			'processingState' => $this->processingState,
			'valid' => $this->valid,
			'token' => $this->token,
			'tokenTimestamp' => $this->tokenTimestamp
		);
		
		return $asynchronousResponse;
	}
}



?>