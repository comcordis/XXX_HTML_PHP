<?php



class XXX_Component_MaskedCharacterLineInput extends XXX_Component_CharacterLineInput
{
	public $CLASS_NAME = 'XXX_Component_MaskedCharacterLineInput';
	
	protected $inputType = 'maskedCharacterLineInput';
				
	public function __construct ($ID = '', $name = '', $nativeForm = false)
	{
		parent::__construct($ID, $name, $nativeForm);
		
		$this->setPresentation(array(
			'characterDisplay' => 'masked',
			'lineCharacterLength' => 16
		));
	}
}


?>