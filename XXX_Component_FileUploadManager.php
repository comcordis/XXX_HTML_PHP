<?php

class XXX_Component_FileUploadManager
{
	const CLASS_NAME = 'XXX_Component_FileUploadManager';

	public static $instanceCounter = 0;
	
	protected $ID = '';
	
	protected $ignoreEmptySlots = true;
	
	protected $files = array
	(
		'files' => array
		(
			'uploaded' => array(),
			'failed' => array()
		),
		
		'fileTotal' => 0,
		'failedFileTotal' => 0,
		'uploadedFileTotal' => 0,
		
		'fileSizeTotal' => 0,
		'failedFileSizeTotal' => 0,
		'uploadedFileSizeTotal' => 0
	);
	
	public function __construct ($ID = '')
	{
		if (!XXX_Type::isValue($ID))
		{
			$ID = 'XXX_Component_FileUploadManager_' . ++self::$instanceCounter;
		}
		
		$this->ID = $ID;
	}
	
	// Failed
	
		public function validateFailedFileFormat (array $failedFile = array())
		{
			$result = false;
			
			if (XXX_Type::isAssociativeArray($failedFile['error']) && ($failedFile['error']['code'] != 4 || !$this->ignoreEmptySlots))
			{
				$result = true;
			}
			
			return $result;			
		}
		
		public function addFailedFile (array $failedFile = array())
		{
			$result = false;
			
			if ($this->validateFailedFileFormat($failedFile))
			{			
				$this->files['files']['failed'][] = $failedFile;
				
				++$this->files['fileTotal'];
				
				++$this->files['failedFileTotal'];
				
				$this->files['fileSizeTotal'] += $failedFile['size'];
				$this->files['failedFileSizeTotal'] += $failedFile['size'];
				
				if (XXX_Debug::$debug)
				{
					XXX_Debug::debugNotification(array(self::CLASS_NAME, 'addFailedFile'), $failedFile['file'] . ' failed: ' . $failedFile['error']['description']);
				}
				
				$result = true;
			}
			else
			{
				if (XXX_Debug::$debug)
				{
					XXX_Debug::debugNotification(array(self::CLASS_NAME, 'addFailedFile'), 'Invalid failedFile format');
				}
			}
			
			return $result;
		}
		
		public function resetFailedFiles ()
		{
			if ($this->files['failedFileTotal'] > 0)
			{
				$this->files['fileTotal'] -= $this->files['failedFileTotal'];
			}
			
			if ($this->files['failedFileSizeTotal'] > 0)
			{
				$this->files['fileSizeTotal'] -= $this->files['failedFileSizeTotal'];
			}
			
			$this->files['failedFileTotal'] = 0;
			$this->files['failedFileSizeTotal'] = 0;
	
			$this->files['files']['failed'] = array();
		}
		
		public function getFailedFiles ()
		{
			return $this->files['files']['failed'];
		}
		
	// Uploaded
		
		public function validateUploadedFileFormat (array $uploadedFile = array())
		{
			$result = false;
			
			if (XXX_Type::isValue($uploadedFile['mimeType']) && XXX_Type::isValue($uploadedFile['extension']) && XXX_Type::isValue($uploadedFile['file']) && XXX_Type::isValue($uploadedFile['checksum']) && $uploadedFile['size'] >= 0)
			{
				$result = true;
			}
			
			return $result;
		}
		
		public function addUploadedFile (array $uploadedFile = array())
		{
			$result = false;
			
			if ($this->validateUploadedFileFormat($uploadedFile))
			{
				$this->files['files']['uploaded'][] = $uploadedFile;
				
				++$this->files['fileTotal'];
				
				++$this->files['uploadedFileTotal'];
				
				$this->files['fileSizeTotal'] += $uploadedFile['size'];
				$this->files['uploadedFileSizeTotal'] += $uploadedFile['size'];
				
				if (XXX_Debug::$debug)
				{
					XXX_Debug::debugNotification(array(self::CLASS_NAME, 'addUploadedFile'), $uploadedFile['file'] . ' uploaded');
				}
				
				$result = true;
			}
			else
			{
				if (XXX_Debug::$debug)
				{
					XXX_Debug::debugNotification(array(self::CLASS_NAME, 'addUploadedFile'), 'Invalid uploadedFile format');
				}
			}
			
			return $result;
		}
	
		public function resetUploadedFiles ()
		{
			if ($this->files['uploadedFileTotal'] > 0)
			{
				$this->files['fileTotal'] -= $this->files['uploadedFileTotal'];
			}
			
			if ($this->files['uploadedFileSizeTotal'] > 0)
			{
				$this->files['fileSizeTotal'] -= $this->files['uploadedFileSizeTotal'];
			}
			
			$this->files['uploadedFileTotal'] = 0;
			$this->files['uploadedFileSizeTotal'] = 0;
	
			$this->files['files']['uploaded'] = array();
		}
		
		public function getUploadedFiles ()
		{
			return $this->files['files']['uploaded'];
		}
	
	public function processFileUploads (array $fileUploads = array())
	{	
		if (XXX_Type::isArray($fileUploads))
		{
			if ($fileUploads['withinRequestBodyLimits'])
			{
				if ($fileUploads['files'])
				{
					$uploadedFiles = $fileUploads['files']['uploaded'];
					
					if ($uploadedFiles)
					{
						for ($i = 0, $iEnd = XXX_Array::getFirstLevelItemTotal($uploadedFiles); $i < $iEnd; ++$i)
						{
							$this->addUploadedFile($uploadedFiles[$i]);
						}
					}
					
					$failedFiles = $fileUploads['files']['failed'];
					
					if ($failedFiles)
					{
						for ($j = 0, $jEnd = XXX_Array::getFirstLevelItemTotal($failedFiles); $j < $jEnd; ++$j)
						{
							$this->addFailedFile($failedFiles[$j]);
						}
					}
				}
			}
			else
			{
				// TODO do something
				XXX_Debug::debugNotification(array(self::CLASS_NAME, 'processFileUploads'), $fileUploads['error']['description']);
			}
		}
	}
	
	public function composeHTML ($composedContent = '')
	{
		$html = '';
		
		
		
		return $html;	
	}
	
	public function getJSInstanceVariable ()
	{
		return $this->ID;
	}
	
	public function composeJS ()
	{
		$js = '';
		
		
		return $js;		
	}
	
}

?>