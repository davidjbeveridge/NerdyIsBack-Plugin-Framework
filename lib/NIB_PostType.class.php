<?php
abstract class NIB_PostType	{
	
	protected $_typeObj;
	protected $_metaBoxObjs;
	protected $_taxonomyObjs;
	
	// You should specify the $_name in your child class
	protected $_name;	// slug
	
	// You should overwrite the following in your child class:
	protected 
		//$label,
		//$labels,
		//$description,
		$public = TRUE,
		//$publicly_queryable,
		//$exclude_from_search,
		$show_ui = TRUE,
		$show_in_menu = TRUE,
		//$menu_position,
		//$menu_icon,
		$capability_type = 'post',
		//$capabilities,
		//$map_meta_cap,
		$hierarchical = FALSE,
		//$supports,
		//$register_meta_box_cb,
		//$taxonomies,
		//$permalink_epmask,
		//$has_archive,
		//$rewrite,
		//$query_var,
		//$can_export,
		//$show_in_nav_menus,
		$_builtin = FALSE,
		$_edit_link = 'post.php?post=%d'
	;
	
	private function getProperties()	{
		$args = get_class_vars(get_called_class());

		unset($args['_typeObj']);
		unset($args['_metaBoxObjs']);
		unset($args['_taxonomyObjs']);
		unset($args['_name']);
		
		return $args;
	}
	
	public function __construct()	{
		$this->_typeObj = new CustomPostType($this->_name,$this->getProperties());
	}
	
}