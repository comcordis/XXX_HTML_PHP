<?php

class XXX_HTML_Page_Composer
{
	// Meta

		public $language = 'en';
		public $dialect = 'US';

		public $title = '';

		public $tags = '';
		public $description = '';

		public $cacheable = true;
		public $indexable = true;
		
		public $compressInternalJS = true;
		public $compressInternalCSS = true;
		
		public $customMetaTags = array
		(
			//array('type' => 'http-equiv', 'key' => 'foo', 'value' => 'bar', 'prefix' => '', 'suffix' => '')
		);

		public $customMeta = '';

	// Presentation
		
		public $standardizePresentation = true;
		
		public $bodyID = '';
		
		public $favicon = false;

		public $externalCSSFiles = array
		(
			//array('publicWebURI' => '', 'prefix' => '', 'suffix' => '')
		);

		public $internalCSS = '';

	// HTML

		public $customHTML = '';

	// JS
		
		public $jsCache = array();
		
		public $externalJSFiles = array
		(
			//array('publicWebURI' => '', 'prefix' => '', 'suffix' => '')
		);

		public $internalJS = '';
		
		public $stripComments = true;
		
		public $stripConcatenatedSpaces = true;

	// Meta

		public function setLanguage ($language = 'en')
		{
			$this->language = $language;
		}

		public function setDialect ($dialect = 'us')
		{
			$this->dialect = $dialect;
		}

		public function setTitle ($title = 'Title')
		{
			$this->title = $title;
		}

		public function appendTitle ($title = '')
		{
			$this->title .= $title;
		}

		public function prependTitle ($title = '')
		{
			$this->title = $title . $this->title;
		}

		public function setTags ($tags = 'Tags')
		{
			$this->tags = $tags;
		}

		public function setDescription ($description = 'Description')
		{
			$this->description = $description;
		}

		public function setCacheable ($cacheable = true)
		{
			$this->cacheable = $cacheable ? true : false;
		}

		public function setIndexable ($indexable = true)
		{
			$this->indexable = $indexable ? true : false;
		}

		public function addCustomMetaTag ($type = 'http-equiv', $key = '', $value = '', $prefix = '', $suffix = '')
		{
			$this->customMetaTags[] = array('type' => $type, 'key' => $key, 'value' => $value, 'prefix' => $prefix, 'suffix' => $suffix);
		}

		public function setCustomMeta ($customMeta = '')
		{
			$this->customMeta = $customMeta;
		}

		public function appendCustomMeta ($customMeta = '')
		{
			$this->customMeta .= XXX_String::$lineSeparator . $customMeta;
		}

		public function prependCustomMeta ($customMeta = '')
		{
			$this->customMeta = $customMeta . XXX_String::$lineSeparator . $this->customMeta;
		}

	// Presentation

		public function setFavicon ($favicon = false)
		{
			$this->favicon = $favicon ? $favicon : false;
		}

		public function addExternalCSSFile ($uri = '', $prefix = '', $suffix = '')
		{
			$this->externalCSSFiles[] = array('uri' => $uri, 'prefix' => $prefix, 'suffix' => $suffix);
		}

		public function setInternalCSS ($internalCSS = '')
		{
			$this->internalCSS = $internalCSS;
		}

		public function appendInternalCSS ($internalCSS = '')
		{
			$this->internalCSS .= XXX_String::$lineSeparator . $internalCSS;
		}

		public function prependInternalCSS ($internalCSS = '')
		{
			$this->internalCSS = $internalCSS . XXX_String::$lineSeparator . $this->internalCSS;
		}
		
		public function setBodyID ($bodyID = '')
		{
			$this->bodyID = '';
		}

	// HTML

		public function setCustomHTML ($customHTML = '')
		{
			$this->customHTML = $customHTML;
		}

		public function appendCustomHTML ($customHTML = '')
		{
			$this->customHTML .= XXX_String::$lineSeparator . $customHTML;
		}

		public function prependCustomHTML ($customHTML = '')
		{
			$this->customHTML = $customHTML . XXX_String::$lineSeparator . $this->customHTML;
		}

	// JS
		
		public function addToJSCache ($key = '', $value = '')
		{
			$this->jsCache[$key] = $value;
		}
		
		public function addExternalJSFile ($uri = '', $prefix = '', $suffix = '', $head = false)
		{
			$this->externalJSFiles[] = array('uri' => $uri, 'prefix' => $prefix, 'suffix' => $suffix, 'head' => $head);
		}

		public function setInternalJS ($internalJS = '')
		{
			$this->internalJS = $internalJS;
		}

		public function appendInternalJS ($internalJS = '', $when = false)
		{
			if ($when === true)
			{
				$when = 'launch';
			}
			
			if ($when)
			{
				$temp = '';
				$temp .= XXX_String::$lineSeparator;
				$temp .= 'XXX.addEventListener(\'' . $when . '\', function ()' . XXX_String::$lineSeparator;
				$temp .= '{' . XXX_String::$lineSeparator;
				$temp .= $internalJS . XXX_String::$lineSeparator;
				$temp .= '});' . XXX_String::$lineSeparator;
				$temp .= XXX_String::$lineSeparator;
				
				$internalJS = $temp;
			}
			
			$this->internalJS .= XXX_String::$lineSeparator . $internalJS;
		}

		public function prependInternalJS ($internalJS = '', $when = false)
		{
			if ($when === true)
			{
				$when = 'launch';
			}
			
			if ($when)
			{
				$temp = '';
				$temp .= XXX_String::$lineSeparator;
				$temp .= 'XXX.addEventListener(\'' . $when . '\', function ()' . XXX_String::$lineSeparator;
				$temp .= '{' . XXX_String::$lineSeparator;
				$temp .= $internalJS . XXX_String::$lineSeparator;
				$temp .= '});' . XXX_String::$lineSeparator;
				$temp .= XXX_String::$lineSeparator;
				
				$internalJS = $temp;
			}
			
			$this->internalJS = $internalJS . XXX_String::$lineSeparator . $this->internalJS;
		}
	
	
	
	public function compose ()
	{
		$result = '';
		
		// doctype: html5
		$result .= '<!DOCTYPE html>';
		
		$result .= '<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->';
		$result .= '<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8"> <![endif]-->';
		$result .= '<!--[if IE 8]><html class="no-js lt-ie9"> <![endif]-->';
		$result .= '<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->';
		
		//$result .= '<html>';
			$result .= '<head>';
				
				// Meta 
					/*
			
					http-equiv: is equivalent to an actual HTTP header
					name: just regular meta data
					
					*/
					// Content types
					$result .= '<meta http-equiv="content-type" content="text/html; charset=utf-8">';
					$result .= '<meta http-equiv="content-style-type" content="text/css; charset=utf-8">';
					$result .= '<meta http-equiv="content-script-type" content="text/javascript; charset=utf-8">';
					
					// Character set
					$result .= '<meta http-equiv="charset" content="utf-8">';
					
					// Content languages
					$result .= '<meta http-equiv="content-language" content="' . $this->language . '-' . $this->dialect . '">';
					$result .= '<meta name="language" content="' . $this->language . '">';
					$result .= '<meta name="dialect" content="' . $this->dialect . '">';
					
					// Content description
					if ($this->title != '')
					{
						$result .= '<title>' . $this->title . '</title>';
					}
					if ($this->tags != '')
					{
						$result .= '<meta name="tags" content="' . $this->tags . '">';
					}
					if ($this->description != '')
					{
						$result .= '<meta name="description" content="' . $this->description . '">';
					}
					
					// Cache
						// Date according to RFC 2068 - http://www.w3.org/Protocols/rfc2068/rfc2068
						if ($this->cacheable)
						{
							// Far future (A year)
							$expiresDate = XXX_I18n_Formatter::formatRFC2965(XXX_TimestampHelpers::getCurrentTimestamp() + 31536000);
						}
						else
						{
							// Far past (A year)
							$expiresDate = XXX_I18n_Formatter::formatRFC2965(XXX_TimestampHelpers::getCurrentTimestamp() - 31536000);
						}
						$result .= '<meta http-equiv="expires" content="' . $expiresDate . '">';
						if ($this->cacheable)
						{
							$result .= '<meta http-equiv="cache-control" content="public">';
						}
						else
						{
							$result .= '<meta http-equiv="pragma" content="no-cache">';
							$result .= '<meta http-equiv="cache-control" content="no-store, no-cache, must-revalidate, post-check=0, pre-check=0">';
							
							XXX_HTTPServer_Client_Output::sendNotCacheableHeaders();				
						}
					
					// Index (Search Engines)
						$result .= '<meta name="revisit-after" content="7 days">';
						$revisionDate = XXX_I18n_Formatter::formatRFC2965(XXX_TimestampHelpers::getCurrentTimestamp());
	
						$result .= '<meta name="date" content="' . $revisionDate . '">';
						$result .= '<meta name="revised" content="' . $revisionDate . '">';
						
						if ($this->indexable)
						{
							$result .= '<meta name="distribution" content="global">';
							$result .= '<meta name="robots" content="index, follow">';
							$result .= '<meta name="googlebot" content="index, follow">';
						}
						else
						{
							$result .= '<meta name="distribution" content="iu">';
							$result .= '<meta name="robots" content="noindex, nofollow">';
							$result .= '<meta name="googlebot" content="noindex, nofollow">';
						}
						
					// Custom meta tags
						
						foreach ($this->customMetaTags as $customMetaTag)
						{
							$result .= $customMetaTag['prefix'] . '<meta ' . $customMetaTag['type'] . '="' . $customMetaTag['key'] . '" content="' . $customMetaTag['value'] . '">' . $customMetaTag['suffix'];
						}
					
					// Custom meta
						
						$result .= $this->customMeta;
					
					// Deployment information
					
						if (XXX_PHP::$debug)
						{
							$deploymentInformation = '<!-- On ' . XXX::$deploymentInformation['timestamp'] . ' [' . XXX::$deploymentInformation['checkout'] . ' (' . XXX::$deploymentInformation['commitHash'] . ')] ' . XXX::$deploymentInformation['deployEnvironment'] . ' (' . XXX::$deploymentInformation['deployIteration'] . ') was deployed by ' . XXX::$deploymentInformation['deployer'] . ' on ' . XXX_OperatingSystem::$hostname . ' (' . XXX_HTTPServer::$ipAddress . ') -->';
							
							$result .= $deploymentInformation;
						}
					
				// Presentation
					// Unobtrusive (external) and progressively enhanced when supported or possible
					
						$result .= '<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">';
						
						/*
						Mobile devices standardization:
						iPhone/iPod - http://developer.apple.com/safari/library/documentation/AppleApplications/Reference/SafariHTMLRef/Articles/MetaTags.html
						Windows Mobile - http://msdn.microsoft.com/en-us/library/ms890014.aspx
						*/
						
						$result .= '<meta name="viewport" content="initial-scale=1.0, user-scalable=yes">';
						$result .= '<meta name="mobileOptimized" content="960">';
						$result .= '<meta name="handheldFriendly" content="true">';
						
						// Disable inline toolbars etc. that interfere with drag&drop behavior
							
							$result .= '<meta http-equiv="imagetoolbar" content="false">';
							$result .= '<meta name="MSSmartTagsPreventParsing" content="true">';
							$result .= '<meta name="MSThemeCompatible" content="no">';
						
						//  Icon - http://www.favicon.cc TODO transparant/animated 
						if ($this->favicon)
						{
							$result .= '<link rel="icon" href="' . $this->favicon . '" type="image/x-icon">';
							$result .= '<link rel="shortcut icon" href="' . $this->favicon . '" type="image/x-icon">';
						}
						
						// External CSS files
						foreach ($this->externalCSSFiles as $externalCSSFile)
						{
							$result .= $externalCSSFile['prefix'] . '<link rel="stylesheet" type="text/css" media="all" charset="utf-8" href="' . $externalCSSFile['uri'] . '">' . $externalCSSFile['suffix'];
						}
						
						// Internal CSS
						if ($this->internalCSS)
						{
							$result .= '<style type="text/css" media="all" charset="utf-8">';
							$result .= '@charset "utf-8";';
							$result .= $this->internalCSS;
							$result .= '</style>';
						}
						
				// JavaScript
					
					foreach ($this->externalJSFiles as $externalJSFile)
					{
						if ($externalJSFile['head'])
						{
							$result .= $externalJSFile['prefix'] . '<script type="text/javascript" language="javascript" charset="utf-8" src="' . $externalJSFile['uri'] . '"></script>' . $externalJSFile['suffix'];
						}
					}
						
			$result .= '</head>';
			$result .= '<body';
			if ($this->bodyID != '')
			{
				$result .= ' id="' . $this->bodyID . '"';
			}
			$result .= '>';
						
				// Body
					
					/*
					
					1. Support
					2. Content
					3. Navigation
							
					id = js
					classes = css
					
					*/
					
					$result .= $this->customHTML;
					
					
					
					$result .= '<div id="XXX_GoogleMapsAPI_attribution"></div>';
					
					if (XXX_PHP::$debug)
					{
						$result .= '<div class="XXX_liveDebugOutputExpanded" id="XXX_liveDebugOutput">
							<pre id="liveDebugOutput">
							</pre>
						</div>
						<div class="XXX_debugOutputExpanded" id="XXX_debugOutput">
							<pre id="debugOutput">
								' . XXX_PHP::composeErrorOutput() . '
							</pre>
						</div>';
					}
					
					if (XXX::$deploymentInformation['deployEnvironment'] != 'production')
					{
						$result .= '<div class="XXX_deployEnvironmentFlap">
							' . XXX::$deploymentInformation['deployEnvironment'] . '
						</div>';
					}
				
				// JavaScript
					
					// Unobtrusive (external and not prototyping existing elements) and progressively enhanced when supported or possible
					
					// External js files
					foreach ($this->externalJSFiles as $externalJSFile)
					{
						if (!$externalJSFile['head'])
						{
							$result .= $externalJSFile['prefix'] . '<script type="text/javascript" language="javascript" charset="utf-8" src="' . $externalJSFile['uri'] . '"></script>' . $externalJSFile['suffix'];
						}
					}
					
					// Internal js
					
					$result .= '<script type="text/javascript" language="javascript" charset="utf-8">';
						$result .= 'var XXX_frameSupport = true;';
					$result .= '</script>';
					$result .= '<noframes>';
						$result .= '<script type="text/javascript" language="javascript" charset="utf-8">';
							$result .= 'XXX_frameSupport = false;';
						$result .= '</script>';
					$result .= '</noframes>'; 
					
					$tempJS = '';
					$tempJS .= "\r\n" . 'XXX.addEventListener(\'beforeLaunch\', function ()
{';
						$tempJS .= "\r\n";
						$tempJS .= XXX_PHP::composeJS();
						$tempJS .= "\r\n";
						
						$tempJS .= "\r\n";
						$tempJS .= XXX_URI::composeJS();
						$tempJS .= "\r\n";
						
						$tempJS .= "\r\n";
						$tempJS .= XXX_I18n_Currency::composeExchangeRatesJS();
						$tempJS .= "\r\n";
												
						if ($this->jsCache != '')
						{
							$tempJS .= "\r\n";
							
							foreach ($this->jsCache as $key => $value)
							{
								$tempJS .= 'XXX.cache.' . $key . ' = ' . XXX_String_JSON::encode($value) . ';' . "\r\n";
							}
							
							$tempJS .= "\r\n";
						}
						
						if (XXX_PHP::$debug)
						{
							$tempJS .= "\r\n" . 'var XXX_liveDebugOutput = XXX_DOM.get(\'XXX_liveDebugOutput\');
								var XXX_debugOutput = XXX_DOM.get(\'XXX_debugOutput\');
								
								XXX_liveDebugOutput.XXX_isVisible = true;
								XXX_debugOutput.XXX_isVisible = true;
								
								XXX_DOM_NativeEventDispatcher.addEventListener(XXX_liveDebugOutput, \'click\', function (nativeEvent)
								{
									nativeEvent.preventDefault();
									nativeEvent.stopPropagation();
									
									if (XXX_liveDebugOutput.XXX_isVisible)
									{
										XXX_liveDebugOutput.XXX_isVisible = false;
										
										XXX_CSS.setClass(XXX_liveDebugOutput, \'XXX_liveDebugOutputCollapsed\');
									}
									else
									{
										
										XXX_liveDebugOutput.XXX_isVisible = true;
										
										XXX_CSS.setClass(XXX_liveDebugOutput, \'XXX_liveDebugOutputExpanded\');
									}
								});
								
								XXX_DOM_NativeEventDispatcher.addEventListener(XXX_debugOutput, \'click\', function (nativeEvent)
								{
									nativeEvent.preventDefault();
									nativeEvent.stopPropagation();
									
									if (XXX_debugOutput.XXX_isVisible)
									{
										XXX_debugOutput.XXX_isVisible = false;
										
										XXX_CSS.setClass(XXX_debugOutput, \'XXX_debugOutputCollapsed\');
									}
									else
									{
										
										XXX_debugOutput.XXX_isVisible = true;
										
										XXX_CSS.setClass(XXX_debugOutput, \'XXX_debugOutputExpanded\');
									}
								});';
						}
					
					$tempJS .= "\r\n" . '});' . "\r\n";
					
					
					$this->internalJS = $tempJS . $this->internalJS;
				
										
					$this->internalJS .= "\r\n" . 'XXX_DOM_Ready.addEventListener(function ()
						{
							XXX.canLaunch();
						});' . "\r\n";
					
					if ($this->internalJS)
					{
						$result .= '<script type="text/javascript" language="javascript" charset="utf-8">';
						$result .= '<!--//--><![CDATA[//><!--' . XXX_OperatingSystem::$lineSeparator;
							$result .= $this->internalJS . XXX_OperatingSystem::$lineSeparator;
						$result .= '//--><!]]>';
						$result .= '</script>';
					}
			
			$result .= '</body>';
		
		$result .= '</html>';
		
		/*
		TODO properly
		if ($this->stripComments)
		{
			$content = XXX_String_Pattern::replace($content, '<!--.*?-->', 's');
		}
		if ($this->stripConcatenatedSpaces)
		{
			$content = XXX_String_Pattern::replace($content, '(?:\s){2,}', '');
		}
		*/
		
		return $result;
	}
}

?>