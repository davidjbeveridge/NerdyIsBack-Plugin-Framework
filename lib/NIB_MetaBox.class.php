<?php
abstract class NIB_MetaBox extends NIB_PropertiesWrapper	{
	
	protected $_metaBoxObj;
	
	protected
		$id,
		$title,
		$context = 'main',
		$priority = 'high',
		$postTypes = array(),
		$fields,
		$tableClass = 'form-table'
	;
	
	public function __construct()	{
		$this->_metaBoxObj = new CustomMetaBox($this->id,$this->title,$this->postTypes);
		$this->_metaBoxObj->setContext($this->context);
		$this->_metaBoxObj->setPriority($this->priority);
		if(!is_null($this->fields))	{
			foreach($this->fields as $field)	{
				$this->_metaBoxObj->addField($field);
			}
		}
		$presenter = new FormTablePresenter($this->fields,'post',$this->tableClass);
		$this->_metaBoxObj->setPresenter($presenter);
	}
	
	public function obj()	{
		return $this->_metaBoxObj;
	}
}