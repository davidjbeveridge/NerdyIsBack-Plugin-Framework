<?php
abstract class NIB_Taxonomy extends NIB_PropertiesWrapper	{
	
	protected $_taxObj;
	
	// You need to overwrite the following:
	protected $_name;	//slug
	
	protected
		//$label,
		//$labels,
		$public = TRUE,
		$show_in_nav_menus = TRUE,
		$show_ui = TRUE,
		$show_tagclound = TRUE,
		$hierarchical = FALSE,
		//$update_count_callback,
		//$rewrite,
		$query_var = TRUE,
		//$capabilities,
		$_builtin = FALSE
	;
	
	public function __construct()	{
		$this->_taxObj = new CustomTaxonomy($this->_name,$this->getProperties());
	}
	
	public function &obj()	{
		return $this->_taxObj;
	}
	
}