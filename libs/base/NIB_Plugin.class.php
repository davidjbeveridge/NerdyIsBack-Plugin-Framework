<?php
abstract class NIB_Plugin	{

	protected $_plugin_instances = array();

	abstract protected function __construct();

	public static final function instance($instance_id,$class)	{

		if(!isset($this->_instances[$instance_id]) && class_exists($class))	{
			$this->_instances[$instance_id] = new $class;
		}
		return $this->_instances[$instance_id];
	}

}