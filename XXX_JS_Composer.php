<?php

abstract class XXX_JS_Composer
{
	public static function composeBoolean ($boolean)
	{
		return $boolean ? 'true' : 'false';
	}
	
	public static function composeString ($string = '')
	{
		return '\'' . XXX_String::addSlashes($string) . '\'';
	}
	
	public static function composeExpandCollapseToggle ($ID = '', $type = '')
	{
		$result = '' . $ID . ' = new XXX_Component_ExpandCollapseToggle(\'' . $ID . '\', \'' . $type . '\');' . XXX_String::$lineSeparator;
		
		return $result;
	}
}

?>