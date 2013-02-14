<?php

abstract class XXX_HTML
{
	const CLASS_NAME = 'XXX_HTML';
	
	public static $IDCounter = 0;
	
	public static $tabIndexCounter = 0;
	
	public static function convertAttributeKeyAndValueToNative ($attribute)
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
				$attribute['key'] = 'cols';
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
				break;
			case 'transferFormat':
			case 'transportFormat':
			case 'encryptionType':
				$attribute['key'] = 'enctype';
				break;
			case 'tabIndex':
				$attribute['key'] = 'tabindex';
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
			case 'selected':
				$attribute['key'] = 'checked';
				break;
			case 'acceptCharacterSet':
				$attribute['key'] = 'accept-charset';
				
				if (XXX_Type::isArray($attribute['value']))
				{
					$attribute['value'] = XXX_Array::joinValuesToString($attribute['value'], ',');
				}
				break;
			case 'acceptHTTPFileUploadFileMIMETypes':
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
				if (XXX_Type::isBoolean($attribute['value']))
				{
					$attribute['value'] == $attribute['value'] ? 'checked' : '';	
				}
				break;
			case 'selected':
				if (XXX_Type::isBoolean($attribute['value']))
				{
					$attribute['value'] == $attribute['value'] ? 'selected' : '';	
				}
				break;
		}
		
		return $attribute;
	}
	
	public static function validateNativeAttributeValue ($attribute)
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
							if ($attribute['value'] == 'checkbox' || $attribute['value'] == 'radio')
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
					if (!XXX_Type::isValue($attribute['value']) || $attribute['value'] == '')
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
			}
		
		return $valid;
	}
	
	public static function composeOpeningTag ($tagName = 'span', $attributes, $skipConvertAttributeKeyAndValueToNative = false, $skipValidateNativeAttributeValue = false)
	{
		$tagName = XXX_String::convertToLowerCase($tagName);
				
		$openingTag = '<' . $tagName;
		
		foreach ($attributes as $attribute)
		{
			if (!$skipConvertAttributeKeyAndValueToNative)
			{
				$attribute = self::convertAttributeKeyAndValueToNative($attribute);
			}
			
			$valid = true;
			
			if (!$skipValidateNativeAttributeValue)
			{
				$valid = self::validateNativeAttributeValue($attribute);
				
				if (!$valid)
				{
					if (XXX_Debug::$debug) { XXX_Debug::debugNotification(array(self::CLASS_NAME, 'composeOpeningTag'), 'Invalid attribute [' . $attribute['key'] . ': ' . $attribute['value'] . '] for tag "' . $tagName . '"'); }							
				}
			}
			
			if ($valid)
			{
				$openingTag .= ' ' . $attribute['key'] . '="' . $attribute['value'] . '"';
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
		
	public static function composeNativeForm (array $attributes, $content, $fileByteSizeMaximum = 0, $token = '', $tokenTimestamp = 0)
	{
		$result = '';
		
		$defaultAttributes = array
		(
			array
			(
				'key' => 'name',
				'value' => 'form_' . ++self::$IDCounter
			),
			array
			(
				'key' => 'transferMethod',
				'value' => 'post'
			),
			array
			(
				'key' => 'acceptCharacterSet',
				'value' => 'utf-8'
			),
			array
			(
				'key' => 'encryptionType',
				'value' => 'multipart/form-data'
			),
			array
			(
				'key' => 'acceptHTTPFileUploadFileMIMETypes',
				'value' => array
				(
					'video/*',
					'audio/*',
					'image/*'
				)
			),
			array
			(
				'key' => 'browserAutoComplete',
				'value' => true
			),
			array
			(
				'key' => 'browserSpellCheck',
				'value' => true
			)
		);
		
		$attributes = self::mergeAttributes($defaultAttributes, $attributes);
		
		$result .= self::composeOpeningTag('form', $attributes);
		
		if (XXX_Type::isPositiveInteger($fileByteSizeMaximum))
		{
			$tempAttributes = array
			(
				array
				(
					'key' => 'name',
					'value' => 'MAX_FILE_SIZE',
				)
			);
			
			$result .= self::composeNativeHiddenVariable($tempAttributes, $fileByteSizeMaximum);
		}
		
		$result .= '<div class="XXX_Form">';
		
			$result .= $content;
		
		$result .= '</div>';
		
		$name = self::getAttributeValueByKey($attributes, 'name');
		
		if (XXX_Type::isValue($token) && XXX_Type::isPositiveInteger($tokenTimestamp))
		{
			$tempAttributes = array
			(
				array
				(
					'key' => 'name',
					'value' => $name . '_token',
				)
			);
			
			$result .= self::composeNativeHiddenVariable($tempAttributes, $token);
			
			$tempAttributes = array
			(
				array
				(
					'key' => 'name',
					'value' => $name . '_tokenTimestamp',
				)
			);
			
			$result .= self::composeNativeHiddenVariable($tempAttributes, $tokenTimestamp);
		}
		
		$tempAttributes = array
		(
			array
			(
				'key' => 'name',
				'value' => $name . '_submitted',
			)
		);
		
		$result .= self::composeNativeHiddenVariable($tempAttributes, 'submitted');
		
		
		
		$result .= self::composeClosingTag('form');
		
		return $result;
	}
	
	
	public static function composeNativeHiddenVariable (array $attributes = array(), $value = '')
	{
		$result = '';
		
		$defaultAttributes = array
		(
			array
			(
				'key' => 'name',
				'value' => 'hiddenVariable_' . ++self::$IDCounter
			),
			array
			(
				'key' => 'type',
				'value' => 'hidden'
			),
			array
			(
				'key' => 'value',
				'value' => $value
			)
		);
		
		$attributes = self::mergeAttributes($defaultAttributes, $attributes);
		
		$result .= self::composeOpeningTag('input', $attributes);
		
		return $result;
	}
	
	public static function composeNativeTextLine (array $attributes, $value = '', $align = 'left')
	{
		$result = '';
		
		$defaultAttributes = array
		(
			array
			(
				'key' => 'name',
				'value' => 'textLine_' . ++self::$IDCounter
			),
			array
			(
				'key' => 'characterDisplay',
				'value' => 'normal'
			),
			array
			(
				'key' => 'value',
				'value' => $value
			),
			array
			(
				'key' => 'align',
				'value' => $align
			)
		);
		
		$attributes = self::mergeAttributes($defaultAttributes, $attributes);
		
		$result .= self::composeOpeningTag('input', $attributes);
		
		return $result;
	}
	
	public static function composeNativeTextLines (array $attributes, $value = '')
	{
		$result = '';
		
		$defaultAttributes = array
		(
			array
			(
				'key' => 'name',
				'value' => 'textLines_' . ++self::$IDCounter
			),
			array
			(
				'key' => 'lineCharacterLength',
				'value' => 32
			),
			array
			(
				'key' => 'lines',
				'value' => 3
			),
			array
			(
				'key' => 'wrap',
				'value' => true
			)
		);
		
		$attributes = self::mergeAttributes($defaultAttributes, $attributes);	
		
		$result .= self::composeOpeningTag('textarea', $attributes);
		
		$result .= $value;
		
		$result .= self::composeClosingTag('textarea');
		
		return $result;
	}
	
	public static function composeNativeDropDownList (array $attributes, $options = array())
	{
		$result = '';
		
		$defaultAttributes = array
		(
			array
			(
				'key' => 'name',
				'value' => 'dropDownList_' . ++self::$IDCounter
			),
			array
			(
				'key' => 'size',
				'value' => 1
			),
			array
			(
				'key' => 'multiple',
				'value' => false
			)
		);
		
		$attributes = self::mergeAttributes($defaultAttributes, $attributes);
		
		$result .= self::composeOpeningTag('select', $attributes);
		
		foreach ($options as $option)
		{
			$result .= '<option';
			$result .= ' value="' . $option['value'] . '"';
			
			if ($option['selected'])
			{
				$result .= ' selected="selected"';	
			}
			
			$result .= '>';
			$result .= $option['label'];
			$result .= '</option>';
		}
		
		$result .= self::composeClosingTag('select');
		
		return $result;
	}
	
	public static function composeNativeCheckServer (array $attributes, $value = '', $selected = false)
	{
		$result = '';
		
		$defaultAttributes = array
		(
			array
			(
				'key' => 'name',
				'value' => 'checkServer_' . ++self::$IDCounter
			),
			array
			(
				'key' => 'type',
				'value' => 'checkbox'
			),
			array
			(
				'key' => 'value',
				'value' => $value
			),
			array
			(
				'key' => 'checked',
				'value' => $selected
			)
		);
		
		$attributes = self::mergeAttributes($defaultAttributes, $attributes);	
		
		$result .= self::composeOpeningTag('input', $attributes);
		
		return $result;
	}
	
	public static function composeNativeRadioButton (array $attributes, $value = '', $selected = false)
	{
		$result = '';
		
		$defaultAttributes = array
		(
			array
			(
				'key' => 'name',
				'value' => 'radioButton_' . ++self::$IDCounter
			),
			array
			(
				'key' => 'type',
				'value' => 'radio'
			),
			array
			(
				'key' => 'value',
				'value' => $value
			),
			array
			(
				'key' => 'checked',
				'value' => $selected
			)
		);
		
		$attributes = self::mergeAttributes($defaultAttributes, $attributes);	
		
		$result .= self::composeOpeningTag('input', $attributes);
		
		return $result;
	}
	
	public static function composeNativeButton (array $attributes, $label = '')
	{
		$result = '';
		
		$defaultAttributes = array
		(
			array
			(
				'key' => 'name',
				'value' => 'button_' . ++self::$IDCounter
			),
			array
			(
				'key' => 'type',
				'value' => 'normal'
			)
		);
		
		$attributes = self::mergeAttributes($defaultAttributes, $attributes);
		
		$result .= self::composeOpeningTag('button', $attributes);
		
		$result .= $label;
		
		$result .= self::composeClosingTag('button');
		
		return $result;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	public static function composeProgressBar ($ID = '')
	{
		$result = '';
		
		$result .= '<div id="' . $ID . '_container" class="XXX_ProgressBar">';
		
			$result .= '<div class="XXX_ProgressBar_border">';
			
				$result .= '<div class="XXX_ProgressBar_background">';
				
					$result .= '<div id="' . $ID . '_progressBar" class="XXX_ProgressBar_progress">';
					
					$result .= '</div>';
				
				$result .= '</div>';
		
			$result .= '</div>';			
				
			$result .= '<div id="' . $ID . '_progressLabel" class="XXX_ProgressBar_label">';
			
			$result .= '</div>';
			
			$result .= XXX_HTML::composeRoundedCorner('XXX_ProgressBar_roundedCorners_topLeft');
			$result .= XXX_HTML::composeRoundedCorner('XXX_ProgressBar_roundedCorners_topRight');
			$result .= XXX_HTML::composeRoundedCorner('XXX_ProgressBar_roundedCorners_bottomRight');
			$result .= XXX_HTML::composeRoundedCorner('XXX_ProgressBar_roundedCorners_bottomLeft');
		
		$result .= '</div>';
		
		return $result;
	}
	
	
	
	
	
	
	public static function composeIcon ($type, $title = '', $ID = '')
	{
		$result = '';
			
				$result .= '<img src="http://' . XXX_Paths::composeExtendedPath('PublicWeb', 'httpServer_static_XXX', 'presentation/images/icons/transparent.gif') . '" alt="' . XXX_String::addSlashes($title) . '" title="' . XXX_String::addSlashes($title) . '" class="XXX_Icon XXX_Icon_' . $type . '">';
						
		return $result;
	}
	
	public static function composeText ($text = '', $ID = '', $bold = false)
	{
		$result = '';
		
		$result = '<span id="' . $ID . '" class="XXX_Text' . ($bold ? ' XXX_Text_bold' : '') . '">' . $text . '</span>';
		
		return $result;
	}
	
	/*
	public static function composeLink ($ID = '', $uri = '', $title = '', $body = '')
	{
		$result = '';
		
			$result .= '<a';
			
			$result .= ' id="' . $ID . '"';			
			$result .= ' href="' . $uri . '"';			
			$result .= ' title="' . $title . '"';
			
			$result .= '>';
			
			$result .= $body;
			
			$result .= '</a>';
		
		return $result;
	}
	
	public static function composeIconLink ($ID = '', $uri = '', $type = '', $title = '')
	{
		
		$result = '';
		
			$result .= '<a';
			
			$result .= ' id="' . $ID . '"';			
			$result .= ' href="' . $uri . '"';			
			$result .= ' title="' . $title . '"';		
			$result .= ' class="XXX_Component_Link_icon"';
			
			$result .= '>';
			
			$result .= XXX_HTML::composeIcon($type, $title);
			
			$result .= '</a>';
		
		return $result;	
	}
	
		
	
	
	
	*/
	
	
	
	
	
	
	
	public static function composeNativeInlineFrame (array $attributes, $source = '')
	{
		$result = '';
		
		$defaultAttributes = array
		(
			array
			(
				'key' => 'name',
				'value' => 'inlineFrameInput_' . ++self::$IDCounter
			),
			array
			(
				'key' => 'source',
				'value' => $source
			)
		);
		
		$attributes = self::mergeAttributes($defaultAttributes, $attributes);	
		
		$result .= self::composeOpeningTag('iframe', $attributes);
		
		$result .= self::composeClosingTag('iframe');
		
		return $result;
	}
	
	
	public static function composeNativeFileUpload (array $attributes, $fileByteSizeMaximum = 0)
	{
		$result = '';
		
		$defaultAttributes = array
		(
			array
			(
				'key' => 'name',
				'value' => 'fileUpload_' . ++self::$IDCounter
			),
			array
			(
				'key' => 'type',
				'value' => 'file'
			),
			array
			(
				'key' => 'acceptHTTPFileUploadFileMIMETypes',
				'value' => array
				(
					'video/*',
					'audio/*',
					'image/*'
				)
			)
		);
		
		$attributes = self::mergeAttributes($defaultAttributes, $attributes);
		
		if (XXX_Type::isPositiveInteger($fileByteSizeMaximum))
		{
			$tempAttributes = array
			(
				array
				(
					'key' => 'name',
					'value' => 'MAX_FILE_SIZE',
				)
			);
			
			$result .= self::composeHiddenTextLine($tempAttributes, $fileByteSizeMaximum);
		}
		
		$result .= self::composeOpeningTag('input', $attributes);
		
		return $result;
	}
	
	public static function composeNativeOption (array $attributes, $value = '', $label = '', $selected = false)
	{
		$result = '';
		
		$defaultAttributes = array
		(
			array
			(
				'key' => 'value',
				'value' => $value
			),
			array
			(
				'key' => 'label',
				'value' => $label
			),
			array
			(
				'key' => 'selected',
				'value' => $selected
			)
		);
		
		$attributes = self::mergeAttributes($defaultAttributes, $attributes);
		
		$result .= self::composeOpeningTag('option', $attributes);
		
		return $result;
	}
	
	public static function composeNativeOptionGroup (array $attributes, $label = '', $content = '')
	{
		$result = '';
		
		$defaultAttributes = array
		(
			array
			(
				'key' => 'label',
				'value' => $label
			)
		);
		
		$attributes = self::mergeAttributes($defaultAttributes, $attributes);
		
		$result .= self::composeOpeningTag('optgroup', $attributes);
		
		$result .= $content;
		
		$result .= self::composeClosingTag('optgroup');
		
		return $result;
	}
	
	public static function composeNativeListBoxInput (array $attributes, $content = '')
	{
		$result = '';
		
		$defaultAttributes = array
		(
			array
			(
				'key' => 'name',
				'value' => 'listBoxInput_' . ++self::$IDCounter + '[]'
			),
			array
			(
				'key' => 'size',
				'value' => 3
			),
			array
			(
				'key' => 'multiple',
				'value' => true
			)
		);
		
		$attributes = self::mergeAttributes($defaultAttributes, $attributes);
		
		$result .= self::composeOpeningTag('select', $attributes);
		
		$result .= $content;
		
		$result .= self::composeClosingTag('select');
		
		return $result;
	}
	
	public static function composeNativeNormalButton (array $attributes, $label = '')
	{
		$result = '';
		
		$defaultAttributes = array
		(
			array
			(
				'key' => 'buttonType',
				'value' => 'normal'
			)
		);
		
		$attributes = self::mergeAttributes($defaultAttributes, $attributes);
		
		$result .= self::composeButton($attributes, $label);
		
		return $result;
	}
	
	public static function composeNativeResetButton (array $attributes, $label = '')
	{
		$result = '';
		
		$defaultAttributes = array
		(
			array
			(
				'key' => 'buttonType',
				'value' => 'reset'
			)
		);
		
		$attributes = self::mergeAttributes($defaultAttributes, $attributes);
		
		$result .= self::composeButton($attributes, $label);
		
		return $result;
	}
	
	public static function composeNativeSubmitButton (array $attributes, $label = '')
	{
		$result = '';
		
		$defaultAttributes = array
		(
			array
			(
				'key' => 'buttonType',
				'value' => 'submit'
			)
		);
		
		$attributes = self::mergeAttributes($defaultAttributes, $attributes);
		
		$result .= self::composeButton($attributes, $label);
		
		return $result;
	}
	
	public static function composeNativeLink (array $attributes, $uri = '', $label = '')
	{
		$result = '';
		
		$defaultAttributes = array
		(
			array
			(
				'key' => 'uri',
				'value' => $uri
			)
		);
		
		$attributes = self::mergeAttributes($defaultAttributes, $attributes);
		
		$result .= self::composeOpeningTag('a', $attributes);
		
		$result .= $label;
		
		$result .= self::composeClosingTag('a');
		
		return $result;
	}
	
	public static function composeNativeAnchor (array $attributes, $anchor = '', $label = '')
	{
		$result = '';
		
		$defaultAttributes = array
		(
			array
			(
				'key' => 'anchor',
				'value' => $anchor
			)
		);
		
		$attributes = self::mergeAttributes($defaultAttributes, $attributes);
		
		$result .= self::composeOpeningTag('a', $attributes);
		
		$result .= $label;
		
		$result .= self::composeClosingTag('a');
		
		return $result;
	}
	
	
	
	
	
	public static function composeTrigger ($ID = '', $class = '', $label = '', $title = '')
	{
		$result = '';
				
		$result .= '<a';
		$result .= ' href="#"';
		$result .= ' id="' . $ID . '"';
		$result .= ' class="' . $class . '"';
		$result .= ' title="' . $title . '"';
		$result .= '>';
		$result .= $label;
		$result .= '</a>';
		
		return $result;
	}
	
	
	
	
	
	
	
	
	
	
	public static function validateState ($state = '')
	{
		$result = false;
		
		$supportedStates = array
		(
			'none',			
			'camoflaged',
			'highlighted',
			'invalidated',
			'focused',
			'pressed'
		);
		
		if (XXX_Array::hasValue($supportedStates, $state))
		{			
			$result = true;
		}
		
		return $result;
	}
	
			
	public static function composeArea ($ID = '', $styling = array(), $content = '')
	{
		if (!XXX_Type::isAssociativeArray($styling))
		{
			$styling = array();
		}
		
		$defaultStyling = array
		(
		 	'layout' => 'leftShrink',
		 	'state' => 'highlighted',
			'background' => 'none',
			'padding' => 0,
			'cornerSize' => 4,
			'cornerOptions' => 15
		);
		
		$styling = XXX_Array::merge($defaultStyling, $styling);
		
		
		if (!self::validateLayout($styling['layout']))
		{
			$styling['layout'] = 'leftShrink';
		}
		
		$layout = $styling['layout'];
		$visible = XXX_Default::toBoolean($styling['visible'], true);
		
		$state = XXX_Default::toOption($styling['state'], array('none', 'camoflaged', 'highlighted', 'invalidated', 'focused', 'pressed'), 'highlighted');
		$background = XXX_Default::toOption($styling['background'], array('none', 'inset', 'outset'), 'none');		
		$padding = XXX_Default::toMinimumInteger($styling['padding'], 0, 0);
		$cornerSize = XXX_Default::toIntegerRange($styling['cornerSize'], 0, 5, 4);
		$cornerOptions = XXX_Default::toIntegerRange($styling['cornerOptions'], 0, 15, 15);
		
		$result = '';
		
		$result .= '<div id="' . $ID . '_area_layout" class="XXX_layout XXX_layout_' . $layout . ' XXX_Area_' . $state . ' XXX_Area_' . $state . '_' . $cornerSize . ' XXX_Area_corners_' . $cornerSize . '"' . (!$visible ? ' style="display: none"' : '') . '>';
			
			$result .= '<div id="' . $ID . '_area_borderAndBackground" class="XXX_Area_borderAndBackground' . ($background != 'none' ? '_' . $background : '') . '">';
			
				$result .= '<div id="' . $ID . '_area_body" class="XXX_Area_body"' . ($padding > 0 ? ' style="padding: ' . $padding . 'px"' : '') . '>';
					
					$result .= $content;
	
					$result .= XXX_HTML::composeClearFloats();
				
				$result .= '</div>';
			
			$result .= '</div>';
			/*	
			if ($cornerSize > 0 && $cornerOptions > 0)
			{
				if (($cornerOptions & 1) == 1)
				{
					$result .= XXX_HTML::composeRoundedCorner('XXX_Area_corner_topLeft');
				}
				
				if (($cornerOptions & 2) == 2)
				{
					$result .= XXX_HTML::composeRoundedCorner('XXX_Area_corner_topRight');
				}
				
				if (($cornerOptions & 4) == 4)
				{
					$result .= XXX_HTML::composeRoundedCorner('XXX_Area_corner_bottomLeft');
				}
				
				if (($cornerOptions & 8) == 8)
				{
					$result .= XXX_HTML::composeRoundedCorner('XXX_Area_corner_bottomRight');
				}
			}
			*/
		$result .= '</div>';
		
		return $result;
	}
	
	public static function composeDialogArea ($ID = '', $styling = array(), $content = '')
	{
		$styling['layout'] = 'above';
		
		return XXX_HTML::composeArea($ID, $styling, $content);
	}
	
	public static function composeInputOptions ($ID = '', $styling = array(), $options = array())
	{
		$result = '';
				
		$result .= '<ul class="XXX_Input_options">';
			
			foreach ($options as $option)
			{
				$result .= '<li>';
				
					$result .= '<a href="#"';
					
					if ($option['selected'])
					{
						$result .= ' class="selected"';
					}
					
					$result .= '>';
					
					$result .= $option['label'];
					
					if (XXX_String::findFirstPosition($option['label'], '<'))
					{
						$result .= XXX_HTML::composeClearFloats();
					}
					
					$result .= '</a>';
				
				$result .= '</li>';
			}
		
		$result .= '</ul>';
		
		$result = XXX_HTML::composeDialogArea($ID, $styling, $result);
		
		return $result;
	}
	
	
		
	
	
	public static function composeNotification ($ID = '', $styling = array(), $content = '')
	{
		if (!XXX_Type::isAssociativeArray($styling))
		{
			$styling = array();
		}
		
		$defaultStyling = array
		(
		 	'layout' => 'leftShrink',
		 	'type' => 'information',
			'arrow' => 'none',
			'padding' => 0
		);
		
		$styling = XXX_Array::merge($defaultStyling, $styling);
		
		
		if (!self::validateLayout($styling['layout']))
		{
			$styling['layout'] = 'leftShrink';
		}
		
		$layout = $styling['layout'];
		
		$type = XXX_Default::toOption($styling['type'], array('help', 'operation', 'validation', 'confirmation', 'information'), 'information');
		$arrow = XXX_Default::toOption($styling['arrow'], array('none', 'top', 'bottom', 'left', 'right'), 'none');		
		$padding = XXX_Default::toMinimumInteger($styling['padding'], 0, 0);
		
		$result = '';
		
		$result .= '<div id="' . $ID . '_notification_layout" class="XXX_layout_' . $layout . '">';
			
			$result .= '<div id="' . $ID . '_notification" class="XXX_Notification_' . $type . '">';
				
				$result .= '<div class="XXX_Notification_side_offset">';
			
					$result .= '<span class="XXX_Notification_side_top"> </span>';
					
					$result .= '<div class="XXX_Notification_side_left">';
						
						$result .= '<div class="XXX_Notification_side_right">';
				
							$result .= '<div id="' . $ID . '_notification_body" class="XXX_Notification_body"' . ($padding > 0 ? ' style="padding: ' . $padding . 'px"' : '') . '>';
								
								$result .= $content;
				
								$result .= XXX_HTML::composeClearFloats();
							
							$result .= '</div>';
							
							$result .= XXX_HTML::composeRoundedCorner('XXX_Notification_roundedCorners_topLeft');
							$result .= XXX_HTML::composeRoundedCorner('XXX_Notification_roundedCorners_topRight');
							$result .= XXX_HTML::composeRoundedCorner('XXX_Notification_roundedCorners_bottomLeft');
							$result .= XXX_HTML::composeRoundedCorner('XXX_Notification_roundedCorners_bottomRight');
					
						$result .= '</div>';
					
					$result .= '</div>';
					
					$result .= '<span class="XXX_Notification_side_bottom"> </span>';
					
					if ($arrow != 'none')
					{
						$result .= '<span class="XXX_Notification_arrow_' . $arrow . '"> </span>';
					}
					
				$result .= '</div>';
			
			$result .= '</div>';
			
		$result .= '</div>';
		
		return $result;
	}
	
	
	
	
	public static function composeRoundedCorner ($class = '')
	{
		return '<span class="' . $class . '"> </span>';
	}
	
	public static function composeClearFloats ($type = 'both')
	{
		$type = XXX_Default::toOption($type, array('left', 'right', 'both'), 'both');
		
		return '<span class="XXX_clearFloats' . ($type != 'both' ? '_' . $type : '') . '"></span>';
	}
	
	public static function composeTab ($ID = '', $label = '', $title = '', $selected = false)
	{
		$result = '';
		
			$result .= '<li>';
				
				$result .= '<a';
				$result .= ' id="' . $ID . '"';
				$result .= ' href="#"';				
				$result .= ' title="' . $title . '"';
				
				if ($selected)
				{
					$result .= ' class="selected"';	
				}
				
				$result .= '>';
				
				$result .= $label;
				
				$result .= '</a>';
			
			$result .= '</li>';
		
		return $result;
	}
	
	public static function composeTabbedArea ($ID = '', $styling = array(), $tabs = '', $content = '')
	{
		if (!XXX_Type::isAssociativeArray($styling))
		{
			$styling = array();
		}
		
		$defaultStyling = array
		(
		 	'tabsColumnPercentage' => 20,
		 	'side' => 'top',
			'area' => array
			(
				'state' => 'camoflaged',
				'background' => 'none',
				'padding' => 5,
				'cornerSize' => 5,
				'cornerOptions' => 15
			)
		);
		
		$styling = XXX_Array::merge($defaultStyling, $styling);
		
		$styling['width'] = XXX_Default::toIntegerRange($styling['width'], 0, 1000, 0);				
		$styling['tabsColumnPercentage'] = XXX_Default::toIntegerRange($styling['tabsColumnPercentage'], 1, 100, 20);
		$styling['areaColumnPercentage'] = 100 - $styling['tabsColumnPercentage'];
		$styling['side'] = XXX_Default::toOption($styling['side'], array('top', 'left', 'right', 'bottom'), 'bottom');
		
		switch ($styling['side'])
		{
			case 'top':
				$styling['area']['cornerOptions'] = 14;
				break;
			case 'left':
				$styling['area']['cornerOptions'] = 14;
				break;
			case 'right':
				$styling['area']['cornerOptions'] = 13;
				$styling['area']['layout'] = 'rightShrink';
				break;
			case 'bottom':
			default:
				$styling['area']['cornerOptions'] = 11;
				break;
		}		
		
		$result = '';
		
		$result .= '<div id="' . $ID . '">';
			
			$tabsResult = '';
			$tabsResult .= '<div';
			
			if ($styling['side'] == 'left' || $styling['side'] == 'right')
			{
				$tabsResult .= ' style="float: ' . $styling['side'] . '; width: ' . $styling['tabsColumnPercentage'] . '%"';	
			}
			
			$tabsResult .= ' class="XXX_Tabs"';
			$tabsResult .= ' id="' . $ID  . '_tabs"';
			$tabsResult .= '>';
					
				$tabsResult .= '<div id="' . $ID  . '_tabs_side" class="XXX_Tabs_' . $styling['side'] . '">';
										
					$tabsResult .= '<div class="XXX_Tabs_borderAndPadding">';
					
						$tabsResult .= '<ul id="' . $ID . '_tabsList">';
						
							for ($i = 0, $iEnd = XXX_Array::getFirstLevelItemTotal($tabs); $i < $iEnd; ++$i)
							{
								$tab = $tabs[$i];
								
								$tabsResult .= self::composeTab($ID . '_tabLink_' . $i, $tab['label'], $tab['title'], $tab['selected']);
							}
						
						$tabsResult .= '</ul>';
						
						$tabsResult .= XXX_HTML::composeClearFloats();
						
					$tabsResult .= '</div>';
					
					
					$tabsResult .= XXX_HTML::composeClearFloats();
					
					if ($styling['side'] == 'top' || $styling['side'] == 'left')
					{
						$tabsResult .= self::composeRoundedCorner('XXX_Tabs_roundedCorners_topLeft');
					}
					
					if ($styling['side'] == 'top' || $styling['side'] == 'right')
					{
						$tabsResult .= self::composeRoundedCorner('XXX_Tabs_roundedCorners_topRight');
					}
					
					if ($styling['side'] == 'bottom' || $styling['side'] == 'left')
					{
						$tabsResult .= self::composeRoundedCorner('XXX_Tabs_roundedCorners_bottomLeft');
					}
					
					if ($styling['side'] == 'bottom' || $styling['side'] == 'right')
					{
						$tabsResult .= self::composeRoundedCorner('XXX_Tabs_roundedCorners_bottomRight');
					}
					
					
				$tabsResult .= '</div>';
					
			$tabsResult .= '</div>';
			
			$areaResult = '';
			
			$areaResult .= self::composeArea($ID . '_area', $styling['area'], $content);
			
			switch ($styling['side'])
			{
				case 'top':
					$result .= $tabsResult;
					$result .= XXX_HTML::composeClearFloats();
					$result .= $areaResult;
					break;
				case 'bottom':
					$result .= $areaResult;
					$result .= XXX_HTML::composeClearFloats();
					$result .= $tabsResult;
					break;
				case 'left':
				case 'right':
					$result .= $tabsResult;
					$result .= $areaResult;
					break;
			}
						
			$result .= XXX_HTML::composeClearFloats();
		
		$result .= '</div>';
		
		return $result;
	}
	
	public static function composeHorizontalFloatSpacer ($width = 5, $side = 'left')
	{
		$result = '';
		
		$side = XXX_Default::toOption($side, array('left', 'right'), 'left');
		
		$result .= '<span class="XXX_floatSpacer_horizontal_' . $side . '" style="width: ' . $width . 'px"></span>';
		
		return $result;
	}
	
	public static function composeVerticalFloatSpacer ($height = 5)
	{
		$result = '';
		
		$result .= '<span class="XXX_floatSpacer_vertical" style="height: ' . $height . 'px"></span>';
		
		return $result;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public static function validateLayout ($layout = '')
	{
		$result = false;
		
		$supportedLayouts = array
		(
			'above',
			
			'full',
			
			'leftShrink',
			'leftFull',
			'leftOneHalf',
			'leftOneThird',
			'leftTwoThirds',
			'leftOneQuarter',
			'leftTwoQuarters',
			'leftThreeQuarters',
			'leftOneFifth',
			'leftTwoFifths',
			'leftThreeFifths',
			'leftFourFifths',
			
			'rightShrink',
			'rightFull',
			'rightOneHalf',
			'rightOneThird',
			'rightTwoThirds',
			'rightOneQuarter',
			'rightTwoQuarters',
			'rightThreeQuarters',
			'rightOneFifth',
			'rightTwoFifths',
			'rightThreeFifths',
			'rightFourFifths'
		);
		
		if (XXX_Array::hasValue($supportedLayouts, $layout))
		{			
			$result = true;
		}
		
		return $result;
	}
	
	public static function validateFormState ($formState = '')
	{
		$result = false;
		
		$supportedFormStates = array
		(
			'normal',
			'error',
			'focused'
		);
		
		if (XXX_Array::hasValue($supportedFormStates, $formState))
		{			
			$result = true;
		}
		
		return $result;
	}
	
	public static function composeFormDivider ()
	{
		return '<div class="XXX_Form_divider"><hr class="XXX_hidden"></div>';
	}
	
	public static function composeSection ($ID = '', $layout = 'leftShrink', $title = '', $description = '', $composedGroups = '')
	{
		if (!self::validateLayout($layout))
		{
			$layout = 'leftShrink';	
		}
		
		$result = '';
		
		$result .= '<div id="' . $ID . '" class="XXX_layout_' . $layout . '">';
			$result .= XXX_HTML::composeFormDivider();
			$result .= '<a name="' . $ID . '"></a>';
			$result .= '<fieldset>';
				
				if (XXX_Type::isValue($title))
				{
					$result .= '<legend class="XXX_Form_sectionTitle">';						
					$result .= $title;						
					$result .= '</legend>';
				}
				
				if (XXX_Type::isValue($description))
				{
					$result .= '<div class="XXX_Form_sectionDescription">';						
					$result .= $description;						
					$result .= '</div>';
				}
				
				$result .= XXX_HTML::composeClearFloats();
				
				$result .= $composedGroups;					
				$result .= XXX_HTML::composeClearFloats();		
					
			$result .= '</fieldset>';
		$result .= '</div>';
		
		return $result;
	}
	
	public static function composeGroup ($ID = '', $layout = 'leftShrink', $title = '', $description = '', $composedFields = '')
	{
		if (!self::validateLayout($layout))
		{
			$layout = 'leftShrink';	
		}
		
		$result = '';
		
		$result .= '<div id="' . $ID . '" class="XXX_layout_' . $layout . '">';
			$result .= XXX_HTML::composeFormDivider();
			$result .= '<a name="' . $ID . '"></a>';
			$result .= '<fieldset>';
				
				if (XXX_Type::isValue($title))
				{
					$result .= '<legend class="XXX_Form_groupTitle">';						
					$result .= $title;						
					$result .= '</legend>';
				}
				
				if (XXX_Type::isValue($description))
				{
					$result .= '<div class="XXX_Form_groupDescription">';						
					$result .= $description;						
					$result .= '</div>';
				}
				
				$result .= XXX_HTML::composeClearFloats();
				
				$result .= '<hr class="XXX_hidden">';
				
				$result .= $composedFields;				
				$result .= XXX_HTML::composeClearFloats();
				
			$result .= '</fieldset>';
		$result .= '</div>';
		
		return $result;
	}
	
	public static function composeField ($ID = '', $layout = 'leftShrink', $formState = 'normal', $composedQuestion = '', $composedAnswer = '')
	{
		if (!self::validateLayout($layout))
		{
			$layout = 'leftShrink';	
		}
		
		if (!self::validateFormState($formState))
		{
			$formState = 'normal';	
		}
		
		$result = '';
		
		$result .= '<div id="' . $ID . '" class="XXX_layout_' . $layout . '">';
			$result .= '<div class="XXX_Form_state_' . $formState . '">';
				$result .= '<a name="' . $ID . '"></a>';
				
				$result .= $composedQuestion;					
				$result .= $composedAnswer;
							
				$result .= '<hr class="XXX_hidden">';
				$result .= XXX_HTML::composeClearFloats();
					
			$result .= '</div>';
		$result .= '</div>';
		
		
		return $result;
	}
	
	public static function composeQuestion ($ID = '', $layout = 'leftShrink', $alignment = 'left', $title = '', $description = '', $composedIndicators = '')
	{
		if (!self::validateLayout($layout))
		{
			$layout = 'leftShrink';	
		}
		
		if (!($alignment == 'left' || $alignment == 'right'))
		{
			$alignment = 'left';	
		}
		
		$result = '';
		
		$result .= '<div id="' . $ID . '" class="XXX_layout_' . $layout . '">';
			$result .= '<div class="XXX_Form_question_alignment_' . $alignment . '">';				
					$result .= '<div class="XXX_Form_question">';
						$result .= '<a name="' . $ID . '"></a>';
						
						if (XXX_Type::isValue($title))
						{
							$result .= '<label class="XXX_Form_questionTitle">';
							$result .= $title;
							$result .= '</label>';							
						}
						if (XXX_Type::isValue($description))
						{
							$result .= '<label class="XXX_Form_questionDescription">';
							$result .= $description;
							$result .= '</label>';
						}							
						
						$result .= XXX_HTML::composeClearFloats();
						
					$result .= '</div>';
									
					$result .= '<div id="' . $ID . '_indicators" class="XXX_Form_indicators">' .  $composedIndicators . '</div>';
					$result .= XXX_HTML::composeClearFloats();

			$result .= '</div>';
		$result .= '</div>';
															
		return $result;
	}
	
	public static function composeAnswer ($ID = '', $layout = 'leftShrink', $composedControls = '', $composedIndicators = '')
	{
		if (!self::validateLayout($layout))
		{
			$layout = 'leftShrink';	
		}
		
		$result = '';
		
		$result .= '<div id="' . $ID . '" class="XXX_layout_' . $layout . '">';
			$result .= '<div class="XXX_Form_answer">';
				$result .= '<a name="' . $ID . '"></a>';
				
				$result .= $composedControls;
					
				$result .= '<div id="' . $ID . '_indicators" class="XXX_Form_indicators">' .  $composedIndicators . '</div>';
				$result .= XXX_HTML::composeClearFloats();
			$result .= '</div>';
		$result .= '</div>';
		
		return $result;
	}
	
	public static function composeControl ($ID = '', $layout = 'leftShrink', $formState = 'normal', $composedControlInputs = '', $composedIndicators = '')
	{
		if (!self::validateLayout($layout))
		{
			$layout = 'leftShrink';	
		}
		
		if (!self::validateFormState($formState))
		{
			$formState = 'normal';	
		}
		
		$result = '';
		
		$result .= '<div id="' . $ID . '" class="XXX_layout_' . $layout . '">';
			$result .= '<div class="XXX_Form_control">';
				$result .= '<a name="' . $ID . '"></a>';
				
					$result .= $composedControlInputs;
				
				$result .= '<div id="' . $ID . '_indicators" class="XXX_Form_indicators">' .  $composedIndicators . '</div>';
				$result .= XXX_HTML::composeClearFloats();
				
			$result .= '</div>';
			
			$result .= XXX_HTML::composeClearFloats();
			
		$result .= '</div>';
		
		return $result;
	}
	
	// TODO $ID for labels to link to
		
	public static function composeInput ($ID = '', $IDForLabels = '', $prefix = '', $heading = '', $caption = '', $suffix = '', $composedInputs = '')
	{
		$result = '';
		
		$result .= '<div id="' . $ID . '_input" class="XXX_Form_Input">';
		
			if (XXX_Type::isValue($prefix))
			{
				$result .= '<label' . ($ID ? ' id="' . $ID . '_prefix"' : '') . ($IDForLabels ? ' for="' . $IDForLabels . '"' : '') . ' class="XXX_Form_Input_prefix">';
				
				if (XXX_Type::isValue($heading))
				{
					$result .= '<br>';
				}
				
				$result .= $prefix;
				
				$result .= '</label>';
			}
			$result .= '<div class="XXX_Form_Input_headingInputCaption">';
				
				if (XXX_Type::isValue($heading))
				{
					$result .= '<label' . ($ID ? ' id="' . $ID . '_heading"' : '') . ($IDForLabels ? ' for="' . $IDForLabels . '"' : '') . ' class="XXX_Form_Input_heading">';
					$result .= $heading;
					$result .= '</label>';
				}
				
				$result .= '<div class="XXX_Form_Input_inner">';
					
					$result .= XXX_HTML::composeInputGrid($ID . '_grid', $composedInputs);
				
					$result .= XXX_HTML::composeClearFloats();
					
				$result .= '</div>';
					
				if (XXX_Type::isValue($caption))
				{
					$result .= '<label' . ($ID ? ' id="' . $ID . '_caption"' : '') . ($IDForLabels ? ' for="' . $IDForLabels . '"' : '') . ' class="XXX_Form_Input_caption">';
					$result .= $caption;
					$result .= '</label>';
				}
			
			$result .= '</div>';
			
			if (XXX_Type::isValue($suffix))
			{
				$result .= '<label' . ($ID ? ' id="' . $ID . '_suffix"' : '') . ($IDForLabels ? ' for="' . $IDForLabels . '"' : '') . ' class="XXX_Form_Input_suffix">';
				
				if (XXX_Type::isValue($heading))
				{
					$result .= '<br>';
				}
				
				$result .= $suffix;
				
				$result .= '</label>';
			}
		
			$result .= XXX_HTML::composeClearFloats();
		
		$result .= '</div>';
		
		return $result;
	}
	
	
	public static function composeInputGrid ($ID = '', $composedInputs = '')
	{
		$result = '';
		
		if (XXX_Type::isArray($composedInputs))
		{
			if (XXX_Array::getDeepestLevel($composedInputs) == 1)
			{
				$composedInputs = array($composedInputs);
			}
			
			$result .= '<ul id="' . $ID . '" class="XXX_Form_Columns">';
			
			$columnCount = 0;
			
			// Columns
			foreach ($composedInputs as $column)
			{
				$result .= '<li id="' . $ID . '_' . $columnCount . '" class="XXX_Form_Column">';
					$result .= '<ul class="XXX_Form_Rows">';
					
					$rowCount = 0;
					
					// Rows
					foreach ($column as $composedInput)
					{
						$result .= '<li id="' . $ID . '_' . $columnCount . '_' . $rowCount . '" class="XXX_Form_Row">';
						$result .= $composedInput;
						$result .= XXX_HTML::composeClearFloats();
						$result .= '</li>';
						
						++$rowCount;
					}					
					
					$result .= '</ul>';
				$result .= '</li>';
				
				++$columnCount;
			}
			
			$result .= '</ul>';
			
			$result .= XXX_HTML::composeClearFloats();
		}
		else
		{
			$result .= $composedInputs;
		}
		
		return $result;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
		
	
	
	
	
	public static function composeSelectOptions ($ID = '', $prefix = '', $allLabel = '', $allTitle = '', $noneLabel = '', $noneTitle = '', $invertLabel = '', $invertTitle = '')
	{
		$result = '';
			
		$result .= XXX_LS;
				
		$result .= '<span class="formInputSelection">';
		
		$result .= $prefix . ': ';
		
		$result .= '<a href="#" id="' . $ID . '_selectAll" title="' . $allTitle . '">' . $allLabel . '</a>';
		$result .= '<a href="#" id="' . $ID . '_selectNone" title="' . $noneTitle . '">' . $noneLabel . '</a>';
		$result .= '<a href="#" id="' . $ID . '_invert" title="' . $invertTitle . '">' . $invertLabel . '</a>';
		
		$result .= '</span>';
		
		$result .= '<br>';
		
		$result .= XXX_LS;
		
		return $result;
	}
	
	public static function composeServer ($type = 'information', $title = 'Information', $messages = array())
	{
		$result = '';
		
		$result .= XXX_LS;
		
		$supportedTypes = array
		(
			'operation',
			'validation',
			'confirmation',
			'information',
			'help'
		);
		
		if (!XXX_Array::hasValue($supportedTypes, $type))
		{
			$type = 'information';
		}
		
		$result .= '<div class="server">';
		
			$result .= '<div class="server' . XXX_String::capitalizeFirstWord($type) . '">';
			
				$result .= '<div class="serverContent">';		
			
					$result .= '<strong class="serverTitle">';
					// icon
					$result .= '<img src="userInterface/clientSide/presentation/images/icon.gif" class="icon_' . $type . '">';
					
					// title
					if (XXX_Type::isValue($title))
					{
						$result .= ' ' . $title;
					}
					
					$result .= '</strong>';
					
					if (XXX_Array::getFirstLevelItemTotal($messages))
					{
						$result .= '<ul>';
						
						foreach ($messages as $message)
						{
							$result .= '<li>' . $message . '</li>';
						}
						
						$result .= '</ul>';
					}
					
				$result .= '</div>';
				
				$result .= '<span class="serverRoundedCornerTopLeft"></span>';
				$result .= '<span class="serverRoundedCornerTopRight"></span>';
				$result .= '<span class="serverRoundedCornerBottomRight"></span>';
				$result .= '<span class="serverRoundedCornerBottomLeft"></span>';
			
			$result .= '</div>';
			
		$result .= '</div>';	
		
		$result .= XXX_HTML::composeClearFloats();
		
		$result .= XXX_LS;
				
		return $result;
		
	}
	
	
	public static function composeColorSwatch ($ID = '', $x = 1, $y = 1, $size = '', $color = '', $content = '')
	{
		$tag = 'span';
		
		if ($content)
		{
			$tag = 'div';
		}
		
		$result = '';
		
		$result .= '<' . $tag;
		
		$result .= ' id="' . $ID . '"';
		
		$result .= ' class="XXX_ColorPicker_swatch"';
		
		$result .= ' style="background-color: ' . $color . '; left: ' . $x . 'px; top: ' . $y . 'px; width: ' . $size . 'px; height: ' . $size . 'px; "';
		
		$result .= ' title="' . $color . '"';
		
		$result .= '>';
		
		$result .= $content;
		
		$result .= '</' . $tag . '>';
		
		return $result;
	}
	
	public static function composeHuePicker ($ID = '', $x = 0, $y = 0)
	{
		$result = '';
		
		$result .= '<div';
		
		$result .= ' title="Click/Drag &amp; release to set the hue"';
		$result .= ' class="XXX_ColorPicker_hueArea"';
		$result .= ' style="left: ' . $x . 'px; top: ' . $y . 'px;"';
		
		$result .= '>';
		
			$result .= '<div';
		
			$result .= ' id="' . $ID . '"';
			$result .= ' title="Click/Drag &amp; release to set the hue"';
			$result .= ' class="XXX_ColorPicker_huePicker"';			
			$result .= '>';
			$result .= '</div>';		
		
		$result .= '</div>';
		
		return $result;
	}
	
	public static function composeSaturationPicker ($ID = '', $x = 0, $y = 0)
	{
		$result = '';
		
		$result .= '<div';		
		$result .= ' title="Click/Drag &amp; release to set the hue"';
		$result .= ' class="XXX_ColorPicker_saturationArea"';
		$result .= ' style="left: ' . $x . 'px; top: ' . $y . 'px;"';		
		$result .= '>';
		
			$result .= '<div class="XXX_ColorPicker_saturationAreaPng"></div>';
		
			$result .= '<div';		
			$result .= ' title="Click/Drag &amp; release to set the saturation"';
			$result .= ' class="XXX_ColorPicker_saturationAreaPngCover"';			
			$result .= '>';
			$result .= '</div>';	
			
			$result .= '<div';
			$result .= ' id="' . $ID . '"';
			$result .= ' title="Click/Drag &amp; release to set the saturation"';
			$result .= ' class="XXX_ColorPicker_saturationPicker"';			
			$result .= '>';
			$result .= '</div>';	
		
		$result .= '</div>';
		
		return $result;
	}
	
	
	
	
	
	
	
	
	
	/*
	
	'none',
	'all',
	'and', => checkServer => $ID = '', $name = '', $tabIndex = 0, $title = '', $value = '', $selected = false
	'or' => radioButton
	
	
	checkboxs/radiobuttons als value de numerieke id van de section
							als ID de ID van het form _sectionTabs
	
	
	
	*/
	
	
	
	
	
	
	
	
	
	
	
	
	
	
		
	public static function composeTable ($ID = '', $caption = '', $summary = '', $composedBodyRows = '', $composedHeaderRows = '', $composedFooterRows = '')
	{
		$result = '';
		
		$result .= '<div class="table">';
				
		$result .= XXX_LS;
				
		$result .= '<span class="tableRoundedCornerTopLeft"></span><span class="tableRoundedCornerTopRight"></span><span class="tableRoundedCornerBottomRight"></span><span class="tableRoundedCornerBottomLeft"></span>';
		
		$result .= XXX_LS;
		
		$result .= '<table';
		
		// ID
		if (XXX_Type::isValue($ID))
		{
			$result .= ' id="' . $ID . '"';
		}
		
		// Summary
		if (XXX_Type::isValue($summary))
		{
			$result .= ' summary="' . $summary . '"';
		}
		
		$result .= '>';
		
		$result .= XXX_LS;
		
		
		// Caption
		if (XXX_Type::isValue($caption))
		{
			$result .= '<caption>' . $caption . '</caption>';
		}
		
		if ($composedHeaderRows && $composedFooterRows)
		{
			$result .= '<thead>';
			
			$result .= $composedHeaderRows;
			
			$result .= '</thead>';
			
			$result .= XXX_LS;
			
			$result .= '<tfoot>';
			
			$result .= $composedFooterRows;
			
			$result .= '</tfoot>';
			
			$result .= XXX_LS;
			
			$result .= '<tbody>';
			
			$result .= $composedBodyRows;
			
			$result .= '</tbody>';
		}
		else
		{
			$result .= $composedBodyRows;	
		}
		
		
		$result .= '</table>';
		
		$result .= XXX_LS;
		
		$result .= '</div>';
		
		$result .= XXX_LS;
		
		$result .= XXX_HTML::composeClearFloats();
		
		$result .= XXX_LS;
		
		return $result;
	}
	
	
	public static function composeRows ($rows = '', $defaultCellType = 'data')
	{
		$result = '';
		
		$i = 0;
		
		foreach ($rows as $row)
		{
			$result .= '<tr';
			
			if (($i % 2) == 0)
			{
				$result .= ' class="tableRowEven"';	
			}
			else
			{
				$result .= ' class="tableRowOdd"';	
			}
			
			$result .= '>';
			
			$result .= XXX_LS;
			
			foreach ($row as $cell)
			{
				if ($cell['type'] == 'header')
				{
					$cellTag = 'th';
				}
				else if ($cell['type'] == 'data')
				{
					$cellTag = 'td';
				}
				else
				{
					if ($defaultCellType == 'header')
					{
						$cellTag = 'th';
					}
					else if ($defaultCellType == 'data')
					{
						$cellTag = 'td';
					}
				}
				
				$result .= '<';
				
				$result .= $cellTag;
				
				if (XXX_Type::isArray($cell))
				{
					// columnSpan
					if (XXX_Type::isPositiveInteger($cell['columnSpan']) && $cell['columnSpan'] > 1)
					{
						$result .= ' colspan="' . $cell['columnSpan'] . '"';
					}
					
					// rowSpan
					if (XXX_Type::isPositiveInteger($cell['rowSpan']) && $cell['rowSpan'] > 1)
					{
						$result .= ' rowspan="' . $cell['rowSpan'] . '"';
					}
					
					// title
					if (XXX_Type::isValue($cell['title']))
					{
						$result .= ' title="' . $cell['title'] . '"';
					}
				}
				
				$result .= '>';
				
				// sortable
				if (XXX_Type::isBoolean($cell['sortable']) && $cell['sortable'])
				{
					$result .= '<a href="#" class="sortable" title="Click to sort">';
				}
				
				
				if (XXX_Type::isArray($cell))
				{
					if (XXX_Type::isValue($cell['body']))
					{
						$result .= $cell['body'];
					}
					else
					{
						$result .= '&nbsp;';	
					}
				}
				else
				{
					if (XXX_Type::isValue($cell))
					{
						$result .= $cell;
					}
					else
					{
						$result .= '&nbsp;';	
					}
				}
				
				// sortable
				if (XXX_Type::isBoolean($cell['sortable']) && $cell['sortable'])
				{
					$result .= '</a>';
				}
								
				$result .= '</';
				
				$result .= $cellTag;
				
				$result .= '>';
				
				$result .= XXX_LS;
			}
			
			$result .= '</tr>';
			
			$result .= XXX_LS;
			
			++$i;
		}
		
		return $result;
	}
		
	public static function composeMessageServer ($type = 'information', $caption = '', array $messages = array())
	{
		$result = '';
		
		$result .= XXX_LS;
		
		switch ($type)
		{
			case 'notice':
				$class = 'messageServerNotice';
				break;
			case 'error':
				$class = 'messageServerError';
				break;
			case 'correct':
				$class = 'messageServerCorrect';
				break;
			case 'information':
			default:
				$class = 'messageServerInformation';
				break;
		}
		
		$result .= '<div';
		
		$result .= ' class="' . $class . '"';
		
		$result .= '>';
		
		// caption
		if (XXX_Type::isValue($caption))
		{
			$result .= XXX_LS;
			
			$result .= '<strong>';
			
			$result .= '<img src="userInterface/clientSide/presentation/images/icon.gif" class="icon_' . $class . '">';
			
			$result .= '<span> ';
			
			$result .= $caption;
			
			$result .= '</span>';
			
			$result .= '</strong>';	
		}
		
		// message
		if (XXX_Type::isFilledArray($messages))
		{
			$result .= XXX_LS;
			
			$result .= '<ul>';
			
			foreach ($messages as $message)
			{
				$result .= XXX_LS;
				
				$result .= '<li>';
				
				$result .= $message;
				
				$result .= '</li>';
			}
			
			$result .= '</ul>';
		}
		
		$result .= XXX_LS;
		
		$result .= '</div>';
		
		return $result;
	}
	
	public static function composePaginationLinkItem ($type = '', $uri = '', $title = '', $body = '', $js = '')
	{
		$result = '';
		
		$result .= XXX_LS;
		
		switch ($type)
		{
			case 'edge':
			case 'center':
				$result .= '<li>';
		
				$result .= '<a';
				
				if (XXX_Type::isValue($uri))
				{			
					$result .= ' href="' . $uri . '"';
				}
				
				if (XXX_Type::isValue($title))
				{			
					$result .= ' title="' . $title . '"';
				}
				
				if (XXX_Type::isValue($js))
				{			
					$result .= ' onclick="' . $js . '"';
				}
				
				$result .= '>';
				
				
		$result .= '<span class="paginationRoundedCornerTopLeft"></span><span class="paginationRoundedCornerTopRight"></span><span class="paginationRoundedCornerBottomRight"></span><span class="paginationRoundedCornerBottomLeft"></span>';
				
				if (XXX_Type::isValue($body))
				{			
					$result .= $body;
				}
				
				$result .= '</a>';
				
				$result .= '</li>';
				break;
			case 'step':
			case 'skip':
			case 'end':
				$result .= '<li>';
		
				$result .= '<a';
				
				if (XXX_Type::isValue($uri))
				{			
					$result .= ' href="' . $uri . '"';
				}
				
				if (XXX_Type::isValue($title))
				{			
					$result .= ' title="' . $title . '"';
				}
				
				if (XXX_Type::isValue($js))
				{			
					$result .= ' onclick="' . $js . '"';
				}
				
				$result .= ' class="paginationSide"';
				
				$result .= '>';
				
				
		$result .= '<span class="paginationRoundedCornerTopLeft"></span><span class="paginationRoundedCornerTopRight"></span><span class="paginationRoundedCornerBottomRight"></span><span class="paginationRoundedCornerBottomLeft"></span>';
				
				if (XXX_Type::isValue($body))
				{			
					$result .= $body;
				}
				
				$result .= '</a>';
				
				$result .= '</li>';
				break;
		}
		
		return $result;
	}
	
	
	public static function composePaginationItem ($type = '', $title = '', $body = '')
	{
		$result = '';
		
		$result .= XXX_LS;
		
		switch ($type)
		{
			case 'dots':
				$result .= '<li>';
		
				$result .= '<span';
				
				if (XXX_Type::isValue($title))
				{			
					$result .= ' title="' . $title . '"';
				}
				
				$result .= ' class="paginationDots"';
				
				$result .= '>';
				
		$result .= '<span class="paginationRoundedCornerTopLeft"></span><span class="paginationRoundedCornerTopRight"></span><span class="paginationRoundedCornerBottomRight"></span><span class="paginationRoundedCornerBottomLeft"></span>';
				
				if (XXX_Type::isValue($body))
				{			
					$result .= $body;
				}
				
				$result .= '</span>';
				
				$result .= '</li>';
				break;
			case 'current':
				$result .= '<li>';
		
				$result .= '<span';
				
				if (XXX_Type::isValue($title))
				{			
					$result .= ' title="' . $title . '"';
				}
				
				$result .= ' class="paginationCurrentPage"';
				
				$result .= '>';
				
		$result .= '<span class="paginationRoundedCornerTopLeft"></span><span class="paginationRoundedCornerTopRight"></span><span class="paginationRoundedCornerBottomRight"></span><span class="paginationRoundedCornerBottomLeft"></span>';
				
				if (XXX_Type::isValue($body))
				{			
					$result .= $body;
				}
				
				$result .= '</span>';
				
				$result .= '</li>';
				break;
		}
		
		return $result;	
	}
	
	public static function composePaginationItemList ($composedItems = '')
	{
		$result = '';	
		
		$result .= XXX_LS;
		
		$result .= XXX_HTML::composeClearFloats();
		
		
		$result .= '<ul class="paginationItemList">';
		
		if (XXX_Type::isValue($composedItems))
		{			
			$result .= $composedItems;
		}
		
		$result .= '</ul>';
		
		$result .= XXX_HTML::composeClearFloats();
				
		return $result;
	}
	
	public static function composePaginationChangeRecordsPerPageOption ($value = 1, $title = '', $body = '', $selected = false)
	{
		$result = '';
		
		$result .= XXX_LS;
		
		$result .= '<option';
		
		if (XXX_Type::isValue($value))
		{			
			$result .= ' value="' . $value . '"';
		}
		
		if (XXX_Type::isValue($title))
		{			
			$result .= ' title="' . $title . '"';
		}
		
		if ($selected)
		{			
			$result .= ' selected="selected"';
		}
		
		$result .= '>';
		
		if (XXX_Type::isValue($body))
		{			
			$result .= $body;
		}
		
		$result .= '</option>';
		
		return $result;
	}
	
	public static function composePaginationChangeRecordsPerPage ($action = '', $recordsPerPageParameter = 'recordsPerPage', $recordsPerPageTitle = '', $composedOptions = '', $recordOffsetParameter = 'recordOffset', $recordOffset = 0, $goLabel = '', $changeRecordsPerPageTitle = '')
	{
		$result = '';
		
		$result .= XXX_LS;
		
		
		$result .= '<form';
		
		$result .= ' name="XXX_Pagination_ChangeRecordsPerPage"';
		
		$result .= ' method="get"';
		
		$result .= ' action=""';
		
		$result .= ' accept-charset="utf-8"';
		
		if (XXX_Type::isValue($action))
		{
			$result .= ' onsubmit="' . $action . '"';
		}
		
		$result .= '>';
		
		
		$result .= XXX_LS;
		
		$result .= '<select';
		
		if (XXX_Type::isValue($recordsPerPageParameter))
		{
			$result .= ' name="' . $recordsPerPageParameter . '"';
		}
		
		if (XXX_Type::isValue($action))
		{
			$result .= ' onchange="' . $action . '"';
		}
		
		if (XXX_Type::isValue($recordsPerPageTitle))
		{
			$result .= ' title="' . $recordsPerPageTitle . '"';
		}
		
		$result .= '>';
		
		if (XXX_Type::isValue($composedOptions))
		{			
			$result .= $composedOptions;
		}
		
		$result .= '</select>';
		
		
		
		$result .= XXX_LS;
		
		$result .= '<input';
		
		$result .= ' type="hidden"';
		
		if (XXX_Type::isValue($recordOffsetParameter))
		{
			$result .= ' name="' . $recordOffsetParameter . '"';
		}
		
		if (XXX_Type::isPositiveNumeric($recordOffset))
		{
			$result .= ' value="' . $recordOffset . '"';
		}
				
		$result .= '>';
		
		
		
		
		$result .= XXX_LS;
		
		$result .= '<input';
		
		$result .= ' type="submit"';
		
		$result .= ' name="XXX_Pagination_ChangeRecordsPerPage_submit"';
		
		if (XXX_Type::isValue($goLabel))
		{
			$result .= ' value="' . $goLabel . '"';
		}
		
		if (XXX_Type::isValue($action))
		{
			$result .= ' onclick="' . $action . '"';
		}
		
		if (XXX_Type::isValue($changeRecordsPerPageTitle))
		{
			$result .= ' title="' . $changeRecordsPerPageTitle . '"';
		}
		
				
		$result .= '>';
		
		
		
		
		
		
		$result .= XXX_LS;
		
		$result .= '</form>';
		
		return $result;
	}
	
	
	
	public static function composePaginationJumpToPage ($action = '', $pageParameter = 'page', $currentPage = 1, $size = 5, $pageTotal = 1, $recordsPerPageParameter = 'recordsPerPage', $recordsPerPage = 1, $goLabel = '', $jumpToPageTitle = '')
	{
		$result = '';
		
		$result .= XXX_LS;
		
		
		$result .= '<form';
		
		$result .= ' name="XXX_Pagination_JumpToPage"';
		
		$result .= ' method="get"';
		
		$result .= ' action=""';
		
		$result .= ' accept-charset="utf-8"';
		
		if (XXX_Type::isValue($action))
		{
			$result .= ' onsubmit="' . $action . '"';
		}
		
		$result .= '>';
		
		
		
		
		
		$result .= XXX_LS;
		
		$result .= '<input';
		
		$result .= ' type="text"';
		
		if (XXX_Type::isValue($pageParameter))
		{
			$result .= ' name="' . $pageParameter . '"';
		}
		
		if (XXX_Type::isPositiveNumeric($currentPage))
		{
			$result .= ' value="' . $currentPage . '"';
		}
		
		if (XXX_Type::isPositiveNumeric($size))
		{
			$result .= ' size="' . $size . '"';
		}
				
		$result .= '>';
		
		
		
		$result .= XXX_LS;
		
		$result .= '<input';
		
		$result .= ' type="hidden"';
		
		$result .= ' name="XXX_Pagination_JumpToPage_CurrentPage"';
		
		if (XXX_Type::isPositiveNumeric($currentPage))
		{
			$result .= ' value="' . $currentPage . '"';
		}
				
		$result .= '>';
		
		
		
		$result .= XXX_LS;
		
		$result .= '<input';
		
		$result .= ' type="hidden"';
		
		$result .= ' name="XXX_Pagination_JumpToPage_PageTotal"';
		
		if (XXX_Type::isPositiveNumeric($pageTotal))
		{
			$result .= ' value="' . $pageTotal . '"';
		}
				
		$result .= '>';
		
		
		
		
		
		
		
		$result .= XXX_LS;
		
		$result .= '<input';
		
		$result .= ' type="hidden"';
		
		if (XXX_Type::isValue($recordsPerPageParameter))
		{
			$result .= ' name="' . $recordsPerPageParameter . '"';
		}
		
		if (XXX_Type::isPositiveNumeric($recordsPerPage))
		{
			$result .= ' value="' . $recordsPerPage . '"';
		}
				
		$result .= '>';
		
		
		
		
		
		$result .= XXX_LS;
		
		$result .= '<input';
		
		$result .= ' type="submit"';
		
		$result .= ' name="XXX_Pagination_JumpToPage_submit"';
		
		if (XXX_Type::isValue($goLabel))
		{
			$result .= ' value="' . $goLabel . '"';
		}
		
		if (XXX_Type::isValue($action))
		{
			$result .= ' onclick="' . $action . '"';
		}
		
		if (XXX_Type::isValue($jumpToPageTitle))
		{
			$result .= ' title="' . $jumpToPageTitle . '"';
		}
		
				
		$result .= '>';
		
		
		
		
		
		
		$result .= XXX_LS;
		
		$result .= '</form>';
		
		return $result;
		
		
	}
	
	public static function composeSearchTermHighlighting ($fragment, $highlight)
	{
		return '<span class="XXX_SearchTermHighlight' . $highlight . '">' . $fragment . '</span>';
	}
	
	
	public static function composePaginationInfo ($side = 'left', $composedPaginationInfo = '')
	{
		$result = '';
		
		$result .= XXX_LS;
		
		switch ($side)
		{
			case 'right':
				$class = 'paginationInfoRight';
				$class2 = 'clearFloats_right';
				break;
			case 'left':
				$class = 'paginationInfoLeft';
				$class2 = 'clearFloats_left';
				break;
		}
		
		$result .= '<div';
		
		$result .= ' class="' . $class . '"';
		
		$result .= '>';
		
		$result .= '<span';
		
		$result .= ' class="paginationInfo"';
		
		$result .= '>';
				
		$result .= '<span class="paginationRoundedCornerTopLeft"></span><span class="paginationRoundedCornerTopRight"></span><span class="paginationRoundedCornerBottomRight"></span><span class="paginationRoundedCornerBottomLeft"></span>';
		
		if (XXX_Type::isValue($composedPaginationInfo))
		{
			$result .= $composedPaginationInfo;
		}
		
		$result .= '</span>';
		
		$result .= '</div>';
		
		return $result;	
	}
	
	
}

?>