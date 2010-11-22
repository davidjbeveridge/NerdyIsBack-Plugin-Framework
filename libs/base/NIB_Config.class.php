<?php
class NIB_Config	{
	private $_directives = array();

	public function __construct()	{}

	public function directive($directive,$value=null)	{
		if(!is_null($value))	{
			$this->_directives[$directive] = $value;
		}
		if(isset($this->_directives[$directive]) && !is_null($this->_directives[$directive]))	{
			return $this->_directives[$directive];
		}
		return null;
	}

	public function delete($directive)	{
		if(isset($this->_directives[$directive]))	{
			unset($this->_directives[$directive]);
			return TRUE;
		}
		return FALSE;
	}
}