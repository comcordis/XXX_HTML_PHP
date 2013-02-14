<?php


//////////////////////////////////////////////////
//////////////////////////////////////////////////
//
// XXX_HTML_Composer
//
//////////////////////////////////////////////////
//////////////////////////////////////////////////

	class XXX_HTML_Composer
	{
		const CLASS_NAME = 'XXX_HTML_Composer';
		
		public static $IDCounter = 0;
		
		public static $tabIndexCounter = 0;
		
		public static function convertAttributeKeyAndValueToNative ($attribute, $tagName)
		{
			switch ($attribute['key'])
			{
				case 'ID':
					$attribute['key'] = 'id';
					break;
				case 'columnSpan':
					$attribute['key'] = 'colspan';
					break;
				case 'columns':
				case 'lineCharacterLength':
					if ($tagName == 'textarea')
					{
						$attribute['key'] = 'cols';
					}
					else
					{
						$attribute['key'] = 'size';
					}
					break;
				case 'lines':
					$attribute['key'] = 'rows';
					break;
				case 'rowSpan':
					$attribute['key'] = 'rowspan';
					break;
				case 'source':
					$attribute['key'] = 'src';
					break;
				case 'wrapLines':
					$attribute['key'] = 'wrap';
					break;
				case 'characterLength':
					$attribute['key'] = 'size';
					break;
				case 'characterLengthMaximum':
					$attribute['key'] = 'maxlength';
					break;
				case 'transferMethod':
				case 'transportMethod':
					$attribute['key'] = 'method';
					
					switch ($attribute['value'])
					{
						case 'body':
							$attribute['value'] = 'post';
							break;
						case 'uri':
							$attribute['value'] = 'get';
							break;
					}
					break;
				case 'transferFormat':
				case 'transportFormat':
				case 'encryptionType':
					$attribute['key'] = 'enctype';
					break;
				case 'tabIndex':
					$attribute['key'] = 'tabindex';
					break;
				case 'accessKey':
					$attribute['key'] = 'accesskey';
					break;
				case 'alternativeDescription':
					$attribute['key'] = 'alt';
					break;
				case 'browserAutoComplete':
				case 'autoComplete':
					$attribute['key'] = 'autocomplete';
					break;
				case 'browserSpellCheck':
				case 'spellCheck':
					$attribute['key'] = 'spellcheck';
					break;
				case 'uri':
					$attribute['key'] = 'href';
					break;
				case 'submitURI':
					$attribute['key'] = 'action';
					break;
				case 'anchor':
					$attribute['key'] = 'name';
					break;
				case 'acceptCharacterSet':
					$attribute['key'] = 'accept-charset';
					
					if (XXX_Type::isArray($attribute['value']))
					{
						$attribute['value'] = XXX_Array::joinValuesToString($attribute['value'], ',');
					}
					break;
				case 'acceptFileMIMETypes':
					$attribute['key'] = 'accept';
					
					if (XXX_Type::isArray($attribute['value']))
					{
						$attribute['value'] = XXX_Array::joinValuesToString($attribute['value'], ',');
					}
					break;
				case 'buttonAction':
					$attribute['key'] = 'type';
					
					switch ($attribute['value'])
					{
						case 'custom':
							$attribute['value'] = 'button';
							break;
						case 'submitForm':
							$attribute['value'] = 'submit';
							break;
						case 'resetForm':
							$attribute['value'] = 'reset';
							break;
					}
					break;
				case 'characterDisplay':
					$attribute['key'] = 'type';
					
					switch ($attribute['value'])
					{
						case 'masked':
							$attribute['value'] = 'password';
							break;
						case 'plain':
							$attribute['value'] = 'text';
							break;
					}
					break;
				case 'readOnly':
					$attribute['key'] = 'readonly';
					break;
				case 'selected':
					if ($tagName == 'input')
					{
						$attribute['key'] = 'checked';
					}
					break;
			}
			
			switch ($attribute['key'])
			{
				case 'wrap':
				case 'autocomplete':
				case 'spellcheck':
					if (XXX_Type::isBoolean($attribute['value']))
					{
						$attribute['value'] = $attribute['value'] ? 'on' : 'off';
					}
					break;
				case 'checked':
					if (XXX_Type::isBoolean($attribute['value']) && $attribute['value'])
					{
						$attribute['value'] = 'checked';
					}
					else
					{
						$attribute = false;
					}
					break;
				case 'selected':
					if (XXX_Type::isBoolean($attribute['value']) && $attribute['value'])
					{
						$attribute['value'] = 'selected';	
					}
					else
					{
						$attribute = false;
					}
					break;
				case 'multiple':
					if (XXX_Type::isBoolean($attribute['value']) && $attribute['value'])
					{
						$attribute['value'] = 'multiple';	
					}
					else
					{
						$attribute = false;
					}
					break;
				case 'disabled':
					if (XXX_Type::isBoolean($attribute['value']) && $attribute['value'])
					{
						$attribute['value'] = 'disabled';	
					}
					else
					{
						$attribute = false;
					}
					break;
				case 'readonly':
					if (XXX_Type::isBoolean($attribute['value']) && $attribute['value'])
					{
						$attribute['value'] = 'readonly';	
					}
					else
					{
						$attribute = false;
					}
					break;
			}
			
			return $attribute;
		}
		
		public static function validateNativeAttributeValue ($attribute, $tagName)
		{
			$valid = true;
			
			// Determine validation
							
				if (XXX_Type::isValue($attribute['validation']) && XXX_Array::hasValue(array('positiveInteger', 'value', 'encryptionType', 'characterSet', 'transferMethod', 'buttonType', 'textLineType', 'onOff', 'checked', 'selected', 'multiple'), $attribute['validation']))
				{
					$validation = $attribute['validation'];
				}
				else
				{
					$validation = false;
					
					switch ($attribute['key'])
					{
						case 'id':
						case 'name':
						case 'value':
						case 'title':
						case 'label':
						case 'src':
						case 'class':
						case 'style':
						case 'alt':
						case 'summary':
						case 'action':
						case 'target':
						case 'uri':
							$validation = 'value';
							break;
						case 'method':
							$validation = 'method';
							break;
						case 'enctype':
							$validation = 'encryptionType';
							break;
						case 'accept-charset':
							$validation = 'characterSet';
							break;
						case 'type':
							if ($tagName == 'input')
							{
								if ($attribute['value'] == 'checkbox' || $attribute['value'] == 'radio' || $attribute['value'] == 'file')
								{
									
								}
								else 
								{
									$validation = 'characterDisplay';	
								}
							}
							else if ($tagName == 'button')
							{
								$validation = 'buttonAction';	
							}
							break;
						case 'size':
						case 'maxlength':
						case 'tabindex':
						case 'cols':
						case 'rows':
						case 'colspan':
						case 'rowspan':
							$validation = 'positiveInteger';
							break;
						case 'wrap':
						case 'autocomplete':
						case 'spellcheck':
							$validation = 'onOff';
							break;
						case 'checked':
							$validation = 'checked';
							break;
						case 'selected':
							$validation = 'selected';
							break;
						case 'multiple':
							$validation = 'multiple';
							break;
						case 'disabled':
							$validation = 'disabled';
							break;
						case 'readonly':
							$validation = 'readOnly';
							break;
					}
				}
			
			// Validation
			
				switch ($validation)
				{				
					case 'positiveInteger':
						if (!XXX_Type::isPositiveInteger($attribute['value']))
						{
							$valid = false;
						}
						break;
					case 'value':
						if (!XXX_Type::isValue($attribute['value']) && $attribute['value'] != '')
						{
							$valid = false;
						}
						break;
					case 'encryptionType':
						if (!XXX_Array::hasValue(array('multipart/form-data', 'application/x-www-form-urlencoded', 'text/plain'), $attribute['value']))
						{
							$valid = false;
						}
						break;
					case 'characterSet':										
						if (!XXX_Array::hasValue(array('utf-8'), $attribute['value']))
						{
							$valid = false;
						}
						break;
					case 'transferMethod':
						if (!($attribute['value'] == 'get' || $attribute['value'] == 'post'))
						{
							$valid = false;
						}
					case 'buttonAction':
						if (!($attribute['value'] == 'button' || $attribute['value'] == 'reset' || $attribute['value'] == 'submit'))
						{
							$valid = false;
						}
						break;
					case 'characterDisplay':
						if (!($attribute['value'] == 'text' || $attribute['value'] == 'password' || $attribute['value'] == 'hidden'))
						{
							$valid = false;
						}
						break;
					case 'onOff':
						if (!($attribute['value'] == 'on' || $attribute['value'] == 'off'))
						{
							$valid = false;
						}
						break;
					case 'checked':
						if (!($attribute['value'] == 'checked'))
						{
							$valid = false;
						}
						break;
					case 'selected':
						if (!($attribute['value'] == 'selected'))
						{
							$valid = false;
						}
						break;
					case 'multiple':
						if (!($attribute['value'] == 'multiple'))
						{
							$valid = false;
						}
						break;
					case 'disabled':
						if (!($attribute['value'] == 'disabled'))
						{
							$valid = false;
						}
						break;
					case 'readOnly':
						if (!($attribute['value'] == 'readonly'))
						{
							$valid = false;
						}
						break;
				}
			
			return $valid;
		}
		
		public static function processAttributes ($attributes, $tagName, $skipConvertAttributeKeyAndValueToNative = false, $skipValidateNativeAttributeValue = false)
		{
			// Clear out doubles with different names (which you only know after conversion)
			$processedAttributes = array();
			
			for ($i = 0, $iEnd = XXX_Array::getFirstLevelItemTotal($attributes); $i < $iEnd; ++$i)
			{
				$valid = true;
				
				$attribute = $attributes[$i];
				
				if (!$skipConvertAttributeKeyAndValueToNative)
				{
					$attribute = self::convertAttributeKeyAndValueToNative($attribute, $tagName);
					
					if ($attribute === false)
					{
						$valid = false;
					}
				}
				
				if ($valid)
				{
					if (!$skipValidateNativeAttributeValue)
					{
						$valid = self::validateNativeAttributeValue($attribute, $tagName);
						
						if (!$valid)
						{
							if (XXX_Debug::$debug) { XXX_Debug::debugNotification(array(self::CLASS_NAME, 'processAttributes'), 'Invalid attribute [' . $attribute['key'] . ': ' . $attribute['value'] . '] for tag "' . $tagName . '"'); }							
						}
					}
				}
				
				if ($valid)
				{
					$processedAttributes[$attribute['key']] = $attribute['value'];
				}
			}
			
			return $processedAttributes;
		}
		
		public static function composeOpeningTag ($tagName = 'span', $processedAttributes)
		{
			$tagName = XXX_String::convertToLowerCase($tagName);
			
			$openingTag = '<' . $tagName;
			
			foreach ($processedAttributes as $key => $value)
			{
				if ($value != '')
				{
					$openingTag .= ' ' . $key . '="' . $value . '"';
				}
			}
			
			$openingTag .= '>';
			
			return $openingTag;
		}
		
		public static function composeClosingTag ($tagName = 'span')
		{
			$tagName = XXX_String::convertToLowerCase($tagName);
			
			$closingTag = '</' . $tagName . '>';
			
			return $closingTag;
		}
				
		public static function getAttributeValueByKey (array $attributes, $key)
		{
			$value = false;
			
			foreach ($attributes as $attribute)
			{
				if ($attribute['key'] == $key)
				{
					$value = $attribute['value'];
				}
			}
			
			return $value;
		}
		
		public static function mergeAttributes (array $defaultAttributes, $attributes)
		{
			$mergedAttributes = array();
			
			if (XXX_Type::isFilledArray($attributes))		
			{
				// Overwrite all defaults
				
				foreach ($defaultAttributes as $defaultAttribute)
				{
					$tempAttribute = $defaultAttribute;
					
					foreach ($attributes as $attribute)
					{
						if ($attribute['key'] == $defaultAttribute['key'])
						{
							$tempAttribute = $attribute;
						}
					}
					
					$mergedAttributes[] = $tempAttribute;
				}
				
				// Add new ones
				
				foreach ($attributes as $attribute)
				{
					$hasDefault = false;
					
					foreach ($defaultAttributes as $defaultAttribute)
					{
						if ($defaultAttribute['key'] == $attribute['key'])
						{
							$hasDefault = true;	
						}
					}
					
					if (!$hasDefault)
					{
						$mergedAttributes[] = $attribute;	
					}
				}
			}
			else
			{
				$mergedAttributes = $defaultAttributes;
			}
			
			return $mergedAttributes;
		}
		
		public static function getTabIndex ()
		{
			return ++self::$tabIndexCounter;
		}
		
		public static function getID ()
		{
			return ++self::$IDCounter;
		}
		
		// Native
			
						
			// <form>
			public static function composeNativeForm ($attributes = false, $composedContent = '')
			{
				$ID = 'XXX_Component_Form_' . self::getID();
				
				$defaultAttributes = array
				(
					array('key' => 'ID', 'value' => $ID),
					array('key' => 'name', 'value' => $ID),
					array('key' => 'transferMethod', 'value' => 'post'),
					array('key' => 'acceptCharacterSet', 'value' => 'utf-8'),
					array('key' => 'encryptionType', 'value' => 'multipart/form-data'),
					array('key' => 'acceptFileMIMETypes', 'value' => array('')),
					array('key' => 'browserAutoComplete', 'value' => true),
					array('key' => 'browserSpellCheck', 'value' => true)
				);
				
				$mergedAttributes = self::mergeAttributes($defaultAttributes, $attributes);
				
				$processedAttributes = self::processAttributes($mergedAttributes, 'form', false, false);
				
				$name = self::getAttributeValueByKey($mergedAttributes, 'name');
				
				$result = self::composeOpeningTag('form', $processedAttributes);
				
					$result .= $composedContent;
				
				$result .= self::composeClosingTag('form');
				
				return $result;
			}
			
			// <input name="HTTPServer_Client_Input_Limits_PROFILE">
			public static function composeNativeHTTPServer_Client_Input_LimitsProfileInput ($ID = '', $HTTPServer_Client_Input_LimitsProfile = '')
			{
				$result = '';
				
				if (!XXX_Array::hasKey(XXX_HTTPServer_Client_Input::$profiles, $HTTPServer_Client_Input_LimitsProfile))
				{
					$HTTPServer_Client_Input_LimitsProfile = 'default';
				}
				
				$tempAttributes = array
				(
					array('key' => 'ID', 'value' => $ID),
					array('key' => 'name', 'value' => 'HTTPServer_Client_Input_Limits_PROFILE')
				);
				
				$result .= self::composeNativeHiddenVariableInput($tempAttributes, $HTTPServer_Client_Input_LimitsProfile);
				
				return $result;
			}
			
			// <input name="MAX_FILE_SIZE">
			public static function composeNativeMaxFileSizeClientDirectiveInput ($ID = '', $maximumFileSize = 0)
			{
				$result = '';
				
				if (!XXX_Type::isPositiveInteger($maximumFileSize))
				{
					$maximumFileSize = 0;
				}
				
					$tempAttributes = array
					(
						array('key' => 'ID', 'value' => $ID),
						array('key' => 'name', 'value' => 'MAX_FILE_SIZE')
					);
					
					$result .= self::composeNativeHiddenVariableInput($tempAttributes, $maximumFileSize);
				
				return $result;
			}
						
			// <input name="UPLOAD_IDENTIFIER"> - http://www.scriptorama.nl/browsers/hoe-maak-ik-een-file-upload-progress-bar-met-php
			public static function composeNativeFileUploadProgress_IDInput ($ID = '', $uploadProgressIdentifier = '')
			{
				$result = '';
				
				if ($uploadProgressIdentifier == '')
				{
					$uploadProgressIdentifier = XXX_String::getRandomHash();
				}
				
				$tempAttributes = array
				(
					array('key' => 'ID', 'value' => $ID),
					array('key' => 'name', 'value' => 'UPLOAD_IDENTIFIER')
				);
				
				$result .= self::composeNativeHiddenVariableInput($tempAttributes, $uploadProgressIdentifier);
				
				return $result;
			}
			
			// <input name="APC_UPLOAD_PROGRESS"> - http://www.phpriot.com/articles/php-ajax-file-uploads/3
			
			
			// <input type="hidden">
			public static function composeNativeHiddenVariableInput ($attributes = false, $value = '')
			{
				$ID = 'XXX_Component_HiddenVariableInput_' . self::getID();
				
				$defaultAttributes = array
				(
					array('key' => 'ID', 'value' => $ID),
					array('key' => 'name', 'value' => $ID),
					array('key' => 'characterDisplay', 'value' => 'hidden'),
					array('key' => 'value', 'value' => $value)
				);
				
				$mergedAttributes = self::mergeAttributes($defaultAttributes, $attributes);
				
				$processedAttributes = self::processAttributes($mergedAttributes, 'input', false, false);
								
				return self::composeOpeningTag('input', $processedAttributes);
			}
			
			// <input type="text|password">
			public static function composeNativeCharacterLineInput ($attributes = false, $value = '')
			{
				$ID = 'XXX_Component_CharacterLineInput_' . self::getID();
				
				$defaultAttributes = array
				(
					array('key' => 'ID', 'value' => $ID),
					array('key' => 'name', 'value' => $ID),
					array('key' => 'tabIndex', 'value' => self::getTabIndex()),
					array('key' => 'characterDisplay', 'value' => 'plain'),
					array('key' => 'lineCharacterLength', 'value' => 32),
					array('key' => 'value', 'value' => $value)
				);
				
				$mergedAttributes = self::mergeAttributes($defaultAttributes, $attributes);
				
				$processedAttributes = self::processAttributes($mergedAttributes, 'input', false, false);
				
				return self::composeOpeningTag('input', $processedAttributes);
			}
			
			// <textarea>
			public static function composeNativeCharacterLinesInput ($attributes = false, $value = '')
			{
				$ID = 'XXX_Component_CharacterLinesInput_' . self::getID();
				
				$defaultAttributes = array
				(
					array('key' => 'ID', 'value' => $ID),
					array('key' => 'name', 'value' => $ID),
					array('key' => 'tabIndex', 'value' => self::getTabIndex()),
					array('key' => 'lineCharacterLength', 'value' => 32),
					array('key' => 'lines', 'value' => 3),
					array('key' => 'wrapLines', 'value' => true)
				);
				
				$mergedAttributes = self::mergeAttributes($defaultAttributes, $attributes);
				
				$processedAttributes = self::processAttributes($mergedAttributes, 'textarea', false, false);
				
				return self::composeOpeningTag('textarea', $processedAttributes) . $value . self::composeClosingTag('textarea');
			}
			
			// <button>
			public static function composeNativeButton ($attributes = false, $label = '')
			{
				$ID = 'XXX_Component_Button_' . self::getID();
				
				$defaultAttributes = array
				(
					array('key' => 'ID', 'value' => $ID),
					array('key' => 'name', 'value' => $ID),
					array('key' => 'tabIndex', 'value' => self::getTabIndex()),
					array('key' => 'buttonAction', 'value' => 'custom')
				);
				
				$mergedAttributes = self::mergeAttributes($defaultAttributes, $attributes);	
				
				$processedAttributes = self::processAttributes($mergedAttributes, 'button', false, false);
				
				return self::composeOpeningTag('button', $processedAttributes) . $label . self::composeClosingTag('button');
			}
			
			// <input type="checkbox">
			public static function composeNativeFreeOptionSwitchInput ($attributes = false, $value = '', $selected = false)
			{
				$ID = 'XXX_Component_FreeOptionSwitchInput_' . self::getID();
				
				$defaultAttributes = array
				(
					array('key' => 'ID', 'value' => $ID),
					array('key' => 'name', 'value' => $ID),
					array('key' => 'tabIndex', 'value' => self::getTabIndex()),
					array('key' => 'type', 'value' => 'checkbox'),
					array('key' => 'value', 'value' => $value),
					array('key' => 'checked', 'value' => $selected)
				);
				
				$mergedAttributes = self::mergeAttributes($defaultAttributes, $attributes);	
				
				$processedAttributes = self::processAttributes($mergedAttributes, 'input', false, false);
				
				return self::composeOpeningTag('input', $processedAttributes);
			}
			
			// <input type="radio">
			public static function composeNativeExclusiveOptionSwitchInput ($attributes = false, $value = '', $selected = false)
			{
				$ID = 'XXX_Component_ExclusiveOptionSwitchInput_' . self::getID();
				
				$defaultAttributes = array
				(
					array('key' => 'ID', 'value' => $ID),
					array('key' => 'name', 'value' => $ID),
					array('key' => 'tabIndex', 'value' => self::getTabIndex()),
					array('key' => 'type', 'value' => 'radio'),
					array('key' => 'value', 'value' => $value),
					array('key' => 'checked', 'value' => $selected)
				);
				
				$mergedAttributes = self::mergeAttributes($defaultAttributes, $attributes);	
				
				$processedAttributes = self::processAttributes($mergedAttributes, 'input', false, false);
				
				return self::composeOpeningTag('input', $processedAttributes);
			}
			
			// <option>	
			public static function composeNativeOptionInput ($attributes = false, $value = '', $label = '', $selected = false)
			{
				$ID = 'XXX_HTML4_NativeOptionInput_' . self::getID();
				
				$defaultAttributes = array
				(
					array('key' => 'ID', 'value' => $ID),
					array('key' => 'value', 'value' => $value),
					array('key' => 'label', 'value' => $label),
					array('key' => 'selected', 'value' => $selected)
				);
				
				$mergedAttributes = self::mergeAttributes($defaultAttributes, $attributes);
				
				$processedAttributes = self::processAttributes($mergedAttributes, 'option', false, false);	
				
				return self::composeOpeningTag('option', $processedAttributes) . $label . self::composeClosingTag('option');
			}
			
			// <optgroup>
			public static function composeNativeOptionGroupInput ($attributes = false, $label = '', $composedNativeOptionInputs = '')
			{
				$ID = 'XXX_HTML4_NativeOptionGroupInput_' . self::getID();
				
				$defaultAttributes = array
				(
					array('key' => 'ID', 'value' => $ID),
					array('key' => 'label', 'value' => $label)
				);
				
				$mergedAttributes = self::mergeAttributes($defaultAttributes, $attributes);	
				
				$processedAttributes = self::processAttributes($mergedAttributes, 'optgroup', false, false);
				
				return self::composeOpeningTag('optgroup', $processedAttributes) . $composedNativeOptionInputs . self::composeClosingTag('optgroup');
			}
				
			// <select size="1">
			public static function composeNativeExclusiveOptionListBoxInput ($attributes = false, $composedNativeOptionInputs = '')
			{
				$ID = 'XXX_Component_ExclusiveOptionListBoxInput_' . self::getID();
				
				$defaultAttributes = array
				(
					array('key' => 'ID', 'value' => $ID),
					array('key' => 'name', 'value' => $ID),
					array('key' => 'tabIndex', 'value' => self::getTabIndex()),
					array('key' => 'size', 'value' => 1),
					array('key' => 'multiple', 'value' => false)
				);
				
				$mergedAttributes = self::mergeAttributes($defaultAttributes, $attributes);	
				
				$processedAttributes = self::processAttributes($mergedAttributes, 'select', false, false);
				
				return self::composeOpeningTag('select', $processedAttributes) . $composedNativeOptionInputs . self::composeClosingTag('select');
			}
				
			// <select size="3" multiple="multiple">
			public static function composeNativeFreeOptionListBoxInput ($attributes = false, $composedNativeOptionInputs = '')
			{
				$ID = 'XXX_Component_FreeOptionListBoxInput_' . self::getID();
				
				$defaultAttributes = array
				(
					array('key' => 'ID', 'value' => $ID),
					array('key' => 'name', 'value' => $ID),
					array('key' => 'tabIndex', 'value' => self::getTabIndex()),
					array('key' => 'size', 'value' => 3),
					array('key' => 'multiple', 'value' => true)
				);
				
				$mergedAttributes = self::mergeAttributes($defaultAttributes, $attributes);	
				
				$processedAttributes = self::processAttributes($mergedAttributes, 'select', false, false);
				
				return self::composeOpeningTag('select', $processedAttributes) . $composedNativeOptionInputs . self::composeClosingTag('select');
			}
			
			// <input type="file">
			public static function composeNativeFileUploadInput ($attributes = false)
			{
				$ID = 'XXX_Component_FileUploadInput_' . self::getID();
				
				$defaultAttributes = array
				(
					array('key' => 'ID', 'value' => $ID),
					array('key' => 'name', 'value' => 'Filedata[]'),
					array('key' => 'tabIndex', 'value' => self::getTabIndex()),
					array('key' => 'type', 'value' => 'file'),
					array('key' => 'multiple', 'value' => true),
					array('key' => 'acceptFileMIMETypes', 'value' => array(''))
				);
				
				$mergedAttributes = self::mergeAttributes($defaultAttributes, $attributes);
				
				$processedAttributes = self::processAttributes($mergedAttributes, 'input', false, false);
				
				$result = self::composeOpeningTag('input', $processedAttributes);
				
				return $result;
			}
			
			public static function composeNativeAsynchronousResponse ($content = '', $instanceVariable = '')
			{
				$result = '';
								
				$result .= '<html>';
					$result .= '<head>';
					$result .= '</head>';
					$result .= '<body>';				
						$result .= $content;
						// Internet explorer fix
						$result .= '<script type="text/javascript" language="javascript">' . XXX_String::$lineSeparator;
							$result .= '<!--//--><![CDATA[//><!-- ' . XXX_String::$lineSeparator;
								// Enables cross sub-domain
								
								$result .= 'if (document.domain)' . XXX_String::$lineSeparator;
								$result .= '{' . XXX_String::$lineSeparator;
									
									$domain = XXX_Domain::getDomain();
																		
									$result .= 'document.domain = \'' . $domain['crossSubDomainJSDomain'] . '\';' . XXX_String::$lineSeparator;
								$result .= '}' . XXX_String::$lineSeparator;
								
								$result .= 'function parentCallback ()' . XXX_String::$lineSeparator;
								$result .= '{' . XXX_String::$lineSeparator;
								
									$result .= 'if (window.top.XXX_InstanceCallback)' . XXX_String::$lineSeparator;
									$result .= '{' . XXX_String::$lineSeparator;
										$result .= 'window.top.XXX_InstanceCallback.triggerCallback(\'' . $instanceVariable . '\', \'processAsynchronousResponse\');' . XXX_String::$lineSeparator;
									$result .= '}' . XXX_String::$lineSeparator;
									$result .= 'else if (window.parent.XXX_InstanceCallback)' . XXX_String::$lineSeparator;
									$result .= '{' . XXX_String::$lineSeparator;
										$result .= 'window.parent.XXX_InstanceCallback.triggerCallback(\'' . $instanceVariable . '\', \'processAsynchronousResponse\');' . XXX_String::$lineSeparator;
									$result .= '}' . XXX_String::$lineSeparator;
								$result .= '}' . XXX_String::$lineSeparator;
								
								$result .= 'if (window.onload)' . XXX_String::$lineSeparator;
								$result .= '{' . XXX_String::$lineSeparator;
									$result .= 'window.onload = parentCallback();' . XXX_String::$lineSeparator;									
								$result .= '}' . XXX_String::$lineSeparator;
								$result .= '{' . XXX_String::$lineSeparator;
									$result .= 'parentCallback();' . XXX_String::$lineSeparator;
								$result .= '}' . XXX_String::$lineSeparator;
							
							$result .= ' //--><!]]>' . XXX_String::$lineSeparator;
						$result .= '</script>' . XXX_String::$lineSeparator;
					$result .= '</body>';	
				$result .= '</html>';
				
				return $result;
			}
						
			// <iframe>
			public static function composeNativeInlineFrame ($attributes = false, $source = '')
			{
				$ID = 'XXX_HTML4_NativeInlineFrame_' . self::getID();
				
				$defaultAttributes = array
				(
					array('key' => 'ID', 'value' => $ID),
					array('key' => 'name', 'value' => $ID),
					array('key' => 'tabIndex', 'value' => self::getTabIndex()),
					array('key' => 'source', 'value' => $source)
				);
				
				$mergedAttributes = self::mergeAttributes($defaultAttributes, $attributes);	
				
				$processedAttributes = self::processAttributes($mergedAttributes, 'iframe', false, false);
				
				return self::composeOpeningTag('iframe', $processedAttributes) . self::composeClosingTag('iframe');
			}
			
			public static function composeFeedbackIcon ($ID = '', $type = '')
			{
				return '<img id="' . $ID . '" class="XXX_Component_Input_FeedbackIcon XXX_Component_Input_FeedbackIcon_' . $type . '" src="http://2.static.lamoora.com/transparentPixel.gif">';
			}
			
			public static function composeFeedbackMessage ($color = 'grey', $message = '')
			{
				return '<span class="XXX_Component_Input_FeedbackMessage XXX_Component_Input_FeedbackMessage_' . $color . '">' . $message . '</span>';
			}
			
			// FeedbackMessages
			public static function composeFeedbackMessages ($feedbackMessages = array())
			{
				$composedFeedbackMessages = array();
				
				$count = 0;
							
				if (XXX_Array::getFirstLevelItemTotal($feedbackMessages['operation']))
				{
					for ($i = 0, $iEnd = XXX_Array::getFirstLevelItemTotal($feedbackMessages['operation']); $i < $iEnd; ++$i)
					{
						$composedFeedbackMessages[] = self::composeFeedbackMessage('yellow', $feedbackMessages['operation'][$i]);
						
						++$count;
					}
				}
				
				if (XXX_Array::getFirstLevelItemTotal($feedbackMessages['validation']))
				{
					for ($i = 0, $iEnd = XXX_Array::getFirstLevelItemTotal($feedbackMessages['validation']); $i < $iEnd; ++$i)
					{
						if ($i == 0)
						{
							$composedFeedbackMessages[] = self::composeFeedbackMessage('red', $feedbackMessages['validation'][$i]);
							
							++$count;
						}
					}
				}
				
				if (XXX_Array::getFirstLevelItemTotal($feedbackMessages['confirmation']))
				{
					for ($i = 0, $iEnd = XXX_Array::getFirstLevelItemTotal($feedbackMessages['confirmation']); $i < $iEnd; ++$i)
					{
						$composedFeedbackMessages[] = self::composeFeedbackMessage('green', $feedbackMessages['confirmation'][$i]);
						
						++$count;
					}
				}
				
				if (XXX_Array::getFirstLevelItemTotal($feedbackMessages['information']))
				{
					for ($i = 0, $iEnd = XXX_Array::getFirstLevelItemTotal($feedbackMessages['information']); $i < $iEnd; ++$i)
					{
						$composedFeedbackMessages[] = self::composeFeedbackMessage('grey', $feedbackMessages['information'][$i]);
						
						++$count;
					}
				}
				
				$composedContent = '';
				
				if ($count > 1)
				{
					$composedContent = XXX_Array::joinValuesToString($composedFeedbackMessages, '<br>');
				}
				else if ($count == 1)
				{
					$composedContent = $composedFeedbackMessages[0];
				}
				
				$result = array
				(
					'count' => $count,
					'html' => $composedContent
				);
				
				return $result;
			}
			
			// <span> based
			public static function composeInputField ($ID = '', $composedContent = '', $state = 'camoflaged', $icon = '')
			{
				if (!$ID)
				{
					$ID = 'XXX_HTML4_InputField_' . self::getID();
				}
				
				$state = XXX_Default::toOption($state, array('disabled', 'camoflaged', 'highlighted', 'focused', 'operated', 'invalid', 'confirmed'), 'camoflaged');
				
				if ($icon != '')
				{
					$composedContent = '<img id="' . $ID . '_icon" class="XXX_Component_InputField_icon" src="http://2.static.lamoora.com/InputField_Icon_' . $icon . '.gif">' . $composedContent;
				}
				
				$result = '<span id="' . $ID . '" class="XXX_Component_InputField XXX_Component_InputField_' . $state . '">' . $composedContent . '</span>';
				
				return $result;
			}
			
			// <span>
			public static function composeNativeInlineContainer ($ID = '', $composedContent = '')
			{
				if (!$ID)
				{
					$ID = 'XXX_HTML4_NativeInlineContainer_' . self::getID();
				}
				
				$result = '<span id="' . $ID . '">' . $composedContent . '</span>';
				
				return $result;
			}
			
			// <div>
			public static function composeNativeBlockContainer ($ID = '', $composedContent = '')
			{
				if (!$ID)
				{
					$ID = 'XXX_HTML4_NativeBlockContainer_' . self::getID();
				}
				
				$result = '<div id="' . $ID . '">' . $composedContent . '</div>';
				
				return $result;
			}
			
			public static function composeNativeIcon ($iconType)
			{
				$result = '<img src="' . XXX_Paths::composePublicWebURI('httpServer_static_XXX', 'presentation/images/icons/transparent.gif', false, false) . '" class="XXX_Icon XXX_Icon_' . $iconType . '">';
				
				return $result;
			}
			
			// <a href="">
			public static function composeNativeLink ($attributes = false, $uri = '', $label = '')
			{
				$ID = 'XXX_Component_Link_' . self::getID();
			
				$defaultAttributes = array
				(
					array('key' => 'ID', 'value' => $ID),
					array('key' => 'uri', 'value' => $uri)
				);
				
				$mergedAttributes = self::mergeAttributes($defaultAttributes, $attributes);
				
				$processedAttributes = self::processAttributes($mergedAttributes, 'a', false, false);
				
				return self::composeOpeningTag('a', $processedAttributes) . $label . self::composeClosingTag('a');
			}
			
			// <a name="">
			public static function composeNativeAnchor ($attributes = false, $anchor = '', $label = '')
			{
				$ID = 'XXX_HTML4_NativeAnchor_' . self::getID();
			
				$defaultAttributes = array
				(
					array('key' => 'ID', 'value' => $ID),
					array('key' => 'anchor', 'value' => $anchor)
				);
				
				$mergedAttributes = self::mergeAttributes($defaultAttributes, $attributes);
				
				$processedAttributes = self::processAttributes($mergedAttributes, 'a', false, false);
				
				return self::composeOpeningTag('a', $processedAttributes) . $label . self::composeClosingTag('a');
			}
			
			// <label for="">
			public static function composeInputLabel ($attributes = false, $ID = '', $inputID = '', $label = '')
			{
				$defaultAttributes = array
				(
					array('key' => 'ID', 'value' => $ID),
					array('key' => 'for', 'value' => $inputID)
				);
				
				$mergedAttributes = self::mergeAttributes($defaultAttributes, $attributes);
				
				$processedAttributes = self::processAttributes($mergedAttributes, 'label', false, false);
				
				return self::composeOpeningTag('label', $processedAttributes) . $label . self::composeClosingTag('label');
			}
			
			// <br>
			public static function composeNativeLineBreak ()
			{
				return '<br>';
			}
			
		// Custom
			
			public static function composeCompletionProgress ($ID = '')
			{
				$result = '';
				$result .= '<span id="' . $ID . '_completionProgressBarBackground" class="XXX_FormInput_completionProgressBarBackground">';
				$result .= '<span class="XXX_FormInput_completionProgressBarBackgroundHead"></span>';
				$result .= '<span id="' . $ID . '_completionProgressBar" class="XXX_FormInput_completionProgressBar">';
				$result .= '<span class="XXX_FormInput_completionProgressBarTail"></span>';
				$result .= '<span class="XXX_FormInput_completionProgressBarHead"></span>';
				$result .= '</span>';
				$result .= '</span>';
				
				return $result;
			}
			
			public static function composeUploadingIndicator ($ID = '')
			{
				$result = '';
				$result .= '<div id="' . $ID . '" class="XXX_UploadingIndicator">';
				$result .= '<h1 id="' . $ID . '_progress"></h1>';
				$result .= '<span class="XXX_fileUploadManager_progressBarBackground" id="' . $ID . '_progressBarBackground"><span class="XXX_fileUploadManager_progressBar" id="' . $ID . '_progressBar"></span></span>';
				$result .= XXX_I18n_Translation::get('forms', 'fileUpload', 'uploading');
				$result .= '</div>';
				
				return $result;
			}
			
			public static function composeClearFloats ($type = 'both')
			{
				$type = XXX_Default::toOption($type, array('left', 'right', 'both'), 'both');
				
				return '<span class="XXX_clearFloats' . ($type != 'both' ? '_' . $type : '') . '"></span>';
			}
			
			public static function composeHorizontalFloatSpacer ($width = 5, $side = 'left')
			{
				$side = XXX_Default::toOption($side, array('left', 'right'), 'left');
				
				return '<span class="XXX_floatSpacer_horizontal_' . $side . '" style="width: ' . $width . 'px"></span>';
			}
			
			public static function composeVerticalFloatSpacer ($height = 5)
			{
				return '<span class="XXX_floatSpacer_vertical" style="height: ' . $height . 'px"></span>';
			}
			
			public static function composeBasicGrid ($ID = '', $composedCells = '', $columns = 2)
			{
				if (XXX_Type::isPositiveInteger($composedCells))
				{
					$iEnd = $composedCells;
					
					$composedCells = array();
					
					for ($i = 0; $i < $iEnd; ++$i)
					{
						$composedCells[] = '';
					}
				}
				else if (!XXX_Type::isArray($composedCells))
				{
					$composedCells = array($composedCells);
				}
				
				$composedCellsTotal = XXX_Array::getFirstLevelItemTotal($composedCells);
				
				$calculatedBasicGrid = XXX_Calculate::getBasicGridProperties($composedCellsTotal, $columns);
				
				$result = '';
										
				// Compose
					
					$result .= '<ul id="' . $ID . '" class="XXX_BasicGrid_Columns">';
					
					// Columns
					for ($k = 0, $kEnd = $calculatedBasicGrid['columnTotal']; $k < $kEnd; ++$k)
					{
						$result .= '<li id="' . $ID . '_column_' . $k . '" class="XXX_BasicGrid_Column">';
							$result .= '<ul class="XXX_BasicGrid_Rows">';
							
							// Rows
							for ($l = 0, $lEnd = $calculatedBasicGrid['rowsPerColumn']; $l < $lEnd; ++$l)
							{
								$m = ($k * $calculatedBasicGrid['rowsPerColumn']) + $l;
								
								if ($m < $calculatedBasicGrid['cellTotal'])
								{
									$composedCell = $composedCells[$m];
																
									$result .= '<li id="' . $ID . '_cell_' . $m . '" class="XXX_BasicGrid_Row">';
									
									$result .= $composedCell;
									
									if ($composedCell != '')
									{
										$result .= XXX_HTML::composeClearFloats();
									}
									
									$result .= '</li>';
								}
							}					
							
							$result .= '</ul>';
						$result .= '</li>';
					}
					
					$result .= '</ul>';
					
					$result .= XXX_HTML::composeClearFloats();
				
							
				return $result;
			}
			
			public static function composeOptionSelectionManipulator ($ID = '')
			{
				$result = '';
				
				$result .= '<span id="' . $ID . '" class="XXX_OptionSelectionManipulator">';
					
					$result .= XXX_I18n_Translation::get('optionSelectionManipulator', 'select') . ' ';
					$result .= '<span id="' . $ID . '_selectAllOptions"><a href="#" id="' . $ID . '_selectAllOptions_nativeLink">' . XXX_I18n_Translation::get('optionSelectionManipulator', 'all') . '</a> - </span>';
					$result .= '<span id="' . $ID . '_deselectAllOptions"><a href="#" id="' . $ID . '_deselectAllOptions_nativeLink">' . XXX_I18n_Translation::get('optionSelectionManipulator', 'none') . '</a> - </span>';
					$result .= '<span id="' . $ID . '_invertSelectAllOptions"><a href="#" id="' . $ID . '_invertSelectAllOptions_nativeLink">' . XXX_I18n_Translation::get('optionSelectionManipulator', 'invert') . '</a></span>';
				
				$result .= '</span>';
				
				return $result;
			}
			
			public static function composeSlotCountManipulator ($ID = '')
			{
				$result = '';
				
				$result .= '<span id="' . $ID . '" class="XXX_SlotCountManipulator">';
					
					$result .= XXX_I18n_Translation::get('slotCountManipulator', 'slots')  . ' ';
					$result .= '<span id="' . $ID . '_addSlot"><a href="#" id="' . $ID . '_addSlot_nativeLink">' . XXX_I18n_Translation::get('slotCountManipulator', 'add') . '</a> - </span>';
					$result .= '<span id="' . $ID . '_removeSlot"><a href="#" id="' . $ID . '_removeSlot_nativeLink">' . XXX_I18n_Translation::get('slotCountManipulator', 'remove') . '</a></span>';
				
				$result .= '</span>';
				
				return $result;
			}
			
			public static function composeFileUploadManager ($ID = '')
			{
				$result = '';
				
				$result .= '<div id="' . $ID . '">';
				
				
				$result .= '</div>';
				
				return $result;
			}
	}

?>