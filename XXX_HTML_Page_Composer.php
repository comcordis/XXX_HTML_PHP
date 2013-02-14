<?php

class XXX_HTML_Page_Composer
{
	// Meta

		public $language = 'en';
		public $dialect = 'us';

		public $title = 'Title';

		public $tags = 'Tags';
		public $description = 'Description';

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
		
		public $favicon = false;

		public $externalCSSFiles = array
		(
			//array('publicWebURI' => '', 'prefix' => '', 'suffix' => '')
		);

		public $internalCSS = '';

	// HTML

		public $customHTML = '';

	// JS

		public $externalJSFiles = array
		(
			//array('publicWebURI' => '', 'prefix' => '', 'suffix' => '')
		);

		public $internalJS = '';
		
		public $stripComments = true;
		
		public $stripConcatenatedSpaces = true;

	public function __construct ()
	{
		$this->language = XXX_I18n_Translation::$selectedTranslation;
		$this->dialect = XXX_I18n_Localization::$selectedLocalization;
		
		$title = XXX_I18n_Translation::get('page', 'title');
		
		if ($title != '')
		{
			$this->title = $title;
		}
		
		
		$tags = XXX_I18n_Translation::get('page', 'tags');
		
		if ($tags != '')
		{
			$this->tags = $tags;
		}
				
		$description = XXX_I18n_Translation::get('page', 'description');
		
		if ($description != '')
		{
			$this->description = $description;
		}
				
		$externalCSSFilePrefix = XXX_Paths::composePublicWebURI('httpServer_static_XXX', 'presentation/css', 0, false) . '/';
		$externalImagePrefix = XXX_Paths::composePublicWebURI('httpServer_static_XXX', 'presentation/images', 0, false) . '/';
		$externalJSFilePrefix = XXX_Paths::composePublicWebURI('httpServer_static_XXX', 'logic/clientSide/js', 0, false) . '/';
		$externalJSConfigurationFilePrefix = XXX_Paths::composePublicWebURI('httpServer_static_XXX', 'configuration', 0, false) . '/';
		
		$externalJSTranslationsFilePrefix = XXX_Paths::composePublicWebURI('httpServer_static_XXX', 'i18n/translations', 0, false) . '/';
		$externalJSLocalizationsFilePrefix = XXX_Paths::composePublicWebURI('httpServer_static_XXX', 'i18n/localizations', 0, false) . '/';

		// Presentation

			$this->setFavicon($externalImagePrefix . 'favicon.ico');
			
			if ($this->standardizePresentation)
			{
				$this->addExternalCSSFile($externalCSSFilePrefix . 'standardize.css', '<!-- Standardize presentation, overwrite default browser markup -->' . XXX_String::$lineSeparator);
			}
			$this->addExternalCSSFile($externalCSSFilePrefix . 'XXX.css');

			if (XXX_HTTP_Browser::$browser == 'fireFox')
			{
				$this->addExternalCSSFile($externalCSSFilePrefix . 'XXX_fireFox.css', '<!-- Browser specific fixes -->');
			}
			else if (XXX_HTTP_Browser::$browser == 'internetExplorer')
			{
				$this->addExternalCSSFile($externalCSSFilePrefix . 'XXX_internetExplorer.css', '<!-- Browser specific fixes --><!--[if IE]>', '<![endif]-->');
				$this->addExternalCSSFile($externalCSSFilePrefix . 'XXX_internetExplorer_6.css', '<!--[if lte IE 6]>', '<![endif]-->');
				$this->addExternalCSSFile($externalCSSFilePrefix . 'XXX_internetExplorer_7.css', '<!--[if lte IE 7]>', '<![endif]-->');

				$this->addExternalCSSFile($externalJSFilePrefix . 'XXX_DOM_Ready_internetExplorer.css', '<!-- Actually javaScript --><!--[if IE]>', '<![endif]-->');
			}
			else if (XXX_HTTP_Browser::$browser == 'safari')
			{
				$this->addExternalCSSFile($externalCSSFilePrefix . 'XXX_safari.css', '<!-- Browser specific fixes -->');
			}
			else if (XXX_HTTP_Browser::$browser == 'chrome')
			{
				$this->addExternalCSSFile($externalCSSFilePrefix . 'XXX_chrome.css', '<!-- Browser specific fixes -->');
			}
			else if (XXX_HTTP_Browser::$browser == 'opera')
			{
				$this->addExternalCSSFile($externalCSSFilePrefix . 'XXX_opera.css', '<!-- Browser specific fixes -->');
			}

		// JS
			
			$this->addExternalJSFile($externalJSConfigurationFilePrefix . 'configuration.js');
			$this->addExternalJSFile($externalJSFilePrefix . 'XXX.general.js');
			$this->addExternalJSFile($externalJSFilePrefix . 'XXX.browser.js');
			
			$this->addExternalJSFile($externalJSTranslationsFilePrefix . $this->language . '/translations.' . $this->language . '.js');
						
			$this->addExternalJSFile($externalJSLocalizationsFilePrefix . $this->dialect . '/localizations.' . $this->dialect . '.js');
	}
	
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

		public function addExternalCSSFile ($publicWebURI = '', $prefix = '', $suffix = '')
		{
			$this->externalCSSFiles[] = array('publicWebURI' => $publicWebURI, 'prefix' => $prefix, 'suffix' => $suffix);
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

		public function addExternalJSFile ($publicWebURI = '', $prefix = '', $suffix = '')
		{
			$this->externalJSFiles[] = array('publicWebURI' => $publicWebURI, 'prefix' => $prefix, 'suffix' => $suffix);
		}

		public function setInternalJS ($internalJS = '')
		{
			$this->internalJS = $internalJS;
		}

		public function appendInternalJS ($internalJS = '')
		{
			$this->internalJS .= XXX_String::$lineSeparator . $internalJS;
		}

		public function prependInternalJS ($internalJS = '')
		{
			$this->internalJS = $internalJS . XXX_String::$lineSeparator . $this->internalJS;
		}

	public function compose ()
	{
		$paths = array
		(
			'template' => 'XXX_presentation_html_templates',
			'include' => 'core',
			'cache' => 'cache_templates'
		);

		$template = new XXX_Template('index.html', 'index_html/generic', 0);
		$template->setPaths($paths);
		$template->setup(true);

		if (!$template->areComposedBlocksCached())
		{
			// Meta

					// Cache
						if ($cacheable)
						{
							// Far future (A year)
							$expiresDate = XXX_Formatter::formatRFC2965(XXX_TimestampHelpers::getCurrentTimestamp() + 31536000);
							$template->compose('root.meta.cacheable');
						}
						else
						{
							// Far past (A year)
							$expiresDate = XXX_Formatter::formatRFC2965(XXX_TimestampHelpers::getCurrentTimestamp() - 31536000);
							$template->compose('root.meta.notCacheable');

							XXX_HTTPServer_Client_Output::sendNotCacheableHeaders();
						}

					// Index
						$revisionDate = XXX_Formatter::formatRFC2965(XXX_TimestampHelpers::getCurrentTimestamp());

						if ($indexable)
						{
							$template->compose('root.meta.indexable');
						}
						else
						{
							$template->compose('root.meta.notIndexable');
						}

					$metaVariables = array
					(
						'TITLE' => $this->title,
						'LANGUAGE' => $this->language,
						'DIALECT' => $this->dialect,
						'TAGS' => $this->tags,
						'DESCRIPTION' => $this->description,
						'EXPIRES_DATE' => $expiresDate,
						'REVISION_DATE' => $revisionDate
					);

					$template->setVariable('META', $metaVariables);

					// Custom meta tags

					foreach ($this->customMetaTags as $customMetaTag)
					{
							$template->setVariable('TYPE', $customMetaTag['type']);
							$template->setVariable('KEY', $customMetaTag['key']);
							$template->setVariable('VALUE', $customMetaTag['value']);
							$template->setVariable('PREFIX', $customMetaTag['prefix']);
							$template->setVariable('SUFFIX', $customMetaTag['suffix']);
						$template->compose('root.meta.customMetaTag');
					}

					$template->setVariable('CUSTOM', $this->customMeta);

				$template->compose('root.meta');

			// Presentation

				// Favicon

					if ($this->favicon)
					{
							$template->setVariable('PATH', $this->favicon);
						$template->compose('root.presentation.favicon');
					}

				// External css files

					foreach ($this->externalCSSFiles as $externalCSSFile)
					{
							$template->setVariable('PATH', $externalCSSFile['publicWebURI']);
							$template->setVariable('PREFIX', $externalCSSFile['prefix']);
							$template->setVariable('SUFFIX', $externalCSSFile['suffix']);
						$template->compose('root.presentation.externalCSSFile');
					}

				// Internal css
					
					$internalCSS = $this->internalCSS;
					
					if ($this->compressInternalCSS && !XXX_Debug::$debug)
					{
						$internalCSS = XXX_CSS_Compressor::compressString($internalCSS);
					}
					
					$template->setVariable('INTERNAL_CSS', $internalCSS);

				$template->compose('root.presentation');

			// HTML

				$template->setVariable('CUSTOM', $this->customHTML);

				if (XXX_Debug::$debug)
				{
					$template->setVariable('serverSide_debugNotifications', XXX_Debug::outputNotifications('debugs'));
					$template->setVariable('serverSide_errorNotifications', XXX_Debug::outputNotifications('errors'));

					$serverInformation = '';

					$serverInformation .= '<pre>';
					$serverInformation .= '<br><strong>ip:</strong> ' . XXX_HTTPServer_Client::$ip . '<br>';
					$serverInformation .= '<br><strong>userAgentString:</strong> ' . XXX_HTTP_Browser::$userAgentString . '<br>';
					$serverInformation .= '<br><strong>http client input limits:</strong><br>';
					$serverInformation .= print_r(XXX_PHP::getHTTPServer_Client_Input_Limits(), true);
					$serverInformation .= '<br><strong>raw uri variables:</strong><br>';
					$serverInformation .= print_r(XXX_HTTPServer_Client_Input::getRawURIVariables(), true);
					$serverInformation .= '<br><strong>raw body variables:</strong><br>';
					$serverInformation .= print_r(XXX_HTTPServer_Client_Input::getRawBodyVariables(), true);
					$serverInformation .= '<br><strong>file uploads:</strong><br>';
					$serverInformation .= print_r(XXX_HTTPServer_Client_Input::getFileUploads(), true);
					$serverInformation .= '<br><strong>cookies:</strong><br>';
					$serverInformation .= print_r(XXX_HTTP_Cookie::getVariables(), true);
					$serverInformation .= '<br><strong>session (' . XXX_HTTP_Cookie_Session::$ID . '):</strong><br>';
					$serverInformation .= print_r(XXX_HTTP_Cookie_Session::getVariables(), true);					
					$serverInformation .= '<br><strong>Profiler:</strong><br>';
					
					$points = XXX_Profiler::getPoints();
					
					$serverInformation .= '<table>';
					for ($i = 0, $iEnd = XXX_Array::getFirstLevelItemTotal($points); $i < $iEnd; ++$i)
					{
						$serverInformation .= '<tr><td>' . $points[$i]['points']['from'] . ' - ' . $points[$i]['points']['to'] . ' </td><td>' . $points[$i]['time']['difference'] . 'ms </td><td>' . $points[$i]['memory']['prefix'] . $points[$i]['memory']['difference'] . 'B</td></tr>';
					}
					$serverInformation .= '</table>';
					
					$serverInformation .= '</pre>';

					$template->setVariable('serverInformation', $serverInformation);

					$template->compose('root.html.debug');
				}

				// TODO
				//$template->compose('root.html.accessibility');
				
				$browserRecommendation = XXX_HTTPServer_Client_Input::getURIVariable('browserRecommendation', 'string', '');
				
				// Avoid for crawlers
				if (!XXX_HTTP_Browser::$crawler && $browserRecommendation)
				{
					$template->compose('root.html.browserRecommendations.browserDownloadLinks');
					$template->setVariable('BROWSER_DOWNLOAD_LINKS', $template->getContent('root.html.browserRecommendations.browserDownloadLinks'));
					$template->resetComposedBlock('root.html.browserRecommendations.browserDownloadLinks');

					// Browser Recommendation
					switch ($browserRecommendation)
					{
						case 'unicodeSupport':
							$template->compose('root.html.browserRecommendations.unicodeSupport');
							break;
						case 'cookieSupport':
							$template->compose('root.html.browserRecommendations.cookieSupport');
							break;
						case 'enhancedSupport':
							$template->compose('root.html.browserRecommendations.enhancedSupport');
							break;
						case 'flashSupport':
							$template->compose('root.html.browserRecommendations.flashSupport');
							break;
					}

					$template->compose('root.html.browserRecommendations');
				}

				$template->compose('root.html');

			// JS

				// Avoid for crawlers
				if (!XXX_HTTP_Browser::$crawler && XXX_Type::isEmpty($browserRecommendation))
				{
					// External javaScript files

						foreach ($this->externalJSFiles as $externalJSFile)
						{
								$template->setVariable('PATH', $externalJSFile['publicWebURI']);
								$template->setVariable('PREFIX', $externalJSFile['prefix']);
								$template->setVariable('SUFFIX', $externalJSFile['suffix']);
							$template->compose('root.js.externalJSFile');
						}

					// Internal javaScript

						$internalJS = $this->internalJS;

						// Library

							$library = XXX_String::$lineSeparator;

								$library .= 'XXX_Server.server_ID = \'' . XXX_Server::$server_ID . '\';' . XXX_String::$lineSeparator;
								
								$library .= 'XXX_Configuration_Server.isDevelopmentServer = ' . (XXX_Server::isDevelopmentServer() ? 'true' : 'false') . ';' . XXX_String::$lineSeparator;
								
								$library .= 'XXX_Domain.setParsedDomain(' . XXX_String_JSON::encode(XXX_Domain::getDomain()) . ');' . XXX_String::$lineSeparator;

								$library .= 'XXX_HTTPServer_Client.ip = ' . XXX_String_JSON::encode(XXX_HTTPServer_Client::$ip) . ';' . XXX_String::$lineSeparator;
								$library .= 'XXX_HTTPServer_Client.encryptedConnection = ' . XXX_String_JSON::encode(XXX_HTTPServer_Client::$encryptedConnection) . ';' . XXX_String::$lineSeparator;
								
								foreach (XXX_HTTPServer_Client_Input::$profiles as $key => $value)
								{
									$library .= 'XXX_HTTPServer_Client_Input.addProfile(' . XXX_String_JSON::encode($key) . ', ' . XXX_String_JSON::encode($value) . ');' . XXX_String::$lineSeparator;
								}
								
								//TODO doesn't belong here, both...
								$library .= 'XXX_Account_ClientInput.user_ID = ' . XXX_String_JSON::encode(XXX_Account_ClientInput::$user_ID) . ';' . XXX_String::$lineSeparator;
								$library .= 'XXX_Account_ClientInput.setSpace(' . XXX_String_JSON::encode(XXX_Account_ClientInput::$space) . ');' . XXX_String::$lineSeparator;

								$library .= 'XXX_Paths.addGroup(\'PublicWeb\', \'/\', false);' .  XXX_String::$lineSeparator;
								
								foreach (XXX_Paths::$pathGroups['PublicWeb']['paths'] as $key => $value)
								{
									$library .= 'XXX_Paths.pathGroups.PublicWeb.paths.' . $key . ' = \'' . $value . '\';' . XXX_String::$lineSeparator;
								}
							
							$tempTimestamp = new XXX_Timestamp();
							$parts = $tempTimestamp->parse();
							$date = $parts['year'] . '_' . $parts['month'] . '_' . $parts['date'];
							
							$library .= 'XXX_I18n_Currencies.date = \'' . $date . '\';' . XXX_String::$lineSeparator;
							$library .= 'XXX_I18n_Currencies.exchangeRates = ' . XXX_String_JSON::encode(XXX_I18n_Currency::$exchangeRates) . ';' . XXX_String::$lineSeparator;
							
						$internalJS = $library . XXX_String::$lineSeparator . $internalJS;
						
						if (XXX_HTTP_Cookie_Session::hasApplicationFeedbackMessages())
						{						
							$internalJS .= XXX_String::$lineSeparator . XXX_HTTP_Cookie_Session::composeApplicationFeedbackMessagesJS();
						}
						
						if ($this->compressInternalJS && !XXX_Debug::$debug)
						{						
							$internalJS = XXX_JS_Compressor::compressString($internalJS);
						}	
						
						$template->setVariable('INTERNAL_JS', $internalJS);

					$template->compose('root.js');
				}

			$template->compose('root');
		}
		
		// Should be after session application feedback has been called... otherwise it doesn't invalidate
		XXX_HTTP_Cookie_Session::save();
		
		$content = $template->getContent('root');
		
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
		
		
		return $content;
	}
}

?>