<?php

abstract class XXX_Manager_State extends XXX_EventDispatcher
{
	protected $states = array
	(
		'disabled' => false,
		'valid' => true,
		'editable' => true,
		'visible' => true,
		'invalidated' => true,
		
		'value' => '',
		'selected' => false,
		'selectedOptionValue' => '',
		'selectedOptionValues' => array(),
		'selectedFiles' => array(),
		'date' => array
		(
			'year' => 1970,
			'month' => 1,
			'date' => 1
		)
	);
	
	public function changeState ($property, $state)
	{
		// Only if it's not similar
		if ($this->states[$property] != $state)
		{
			$this->states[$property] = $state;
		}
	}
	
	public function getState ($property)
	{
		return $this->states[$property];
	}
	
	public function setVisible ($visible) { return $this->changeState('visible', $visible ? true : false); }
	public function show () { return $this->changeState('visible', true); }
	public function hide () { return $this->changeState('visible', false); }
	public function isVisible () { return $this->getState('visible'); }
	
	public function setValid ($valid) { return $this->changeState('valid', $valid ? true : false); }
	public function makeValid () { return $this->changeState('valid', true); }
	public function makeInvalid () { return $this->changeState('valid', false); }
	public function isValid () { return $this->getState('valid'); }
	
	public function setDisabled ($disabled) { return $this->changeState('disabled', $disabled ? true : false); }
	public function enable () { return $this->changeState('disabled', false); }
	public function disable () { return $this->changeState('disabled', true); }
	public function isDisabled () { return $this->getState('disabled'); }
	
	public function setEditable ($editable) { return $this->changeState('editable', $editable ? true : false); }
	public function makeEditable () { return $this->changeState('editable', true); }
	public function makeReadOnly () { return $this->changeState('editable', false); }
	public function isEditable () { return $this->getState('editable'); }
	
	public function setSelected ($selected) { return $this->changeState('selected', $selected ? true : false); }
	public function select () { return $this->changeState('selected', true); }
	public function deselect () { return $this->changeState('selected', false); }
	public function isSelected () { return $this->getState('selected'); }
	
	public function getValue () { return $this->getState('value'); }
	public function setValue ($value) { return $this->changeState('value', $value); }
		
	public function getDate () { return $this->getState('date'); }
	public function setDate ($date) { return $this->changeState('date', $date); }
}

?>