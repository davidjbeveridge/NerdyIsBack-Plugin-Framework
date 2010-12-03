<?php
abstract class NIB_PostType extends NIB_PropertiesWrapper	{
	
	protected $_typeObj;
	
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
	
	public static function property($id)	{
		global $post;
		if(!is_null($post))	{
			return get_post_meta($post->ID,$id,TRUE);
		}
		return null;
	}
	
	public function __construct()	{
		$this->_typeObj = new CustomPostType($this->_name,$this->getProperties());
	}
	
	public function addTaxonomy($obj)	{
		if($obj instanceof NIB_Taxonomy)	{
			$this->_typeObj->addTaxonomy($obj->obj());
		}
		if($obj instanceof CustomTaxonomy)	{
			$this->_typeObj->addTaxonomy($obj);
		}
	}
	
	public function addMetaBox($obj)	{
		if($obj instanceof NIB_MetaBox)	{
			$this->_typeObj->addMetaBox($obj->obj());
		}
		if($obj instanceof CustomMetaBox)	{
			$this->_typeObj->addMetaBox($obj);
		}
	}
}