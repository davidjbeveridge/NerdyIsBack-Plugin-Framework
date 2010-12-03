<?php
abstract class NIB_PropertiesWrapper	{
	
	protected function getProperties()	{
		$args = get_class_vars(get_called_class());

		unset($args['_typeObj']);
		unset($args['_metaBoxObjs']);
		unset($args['_taxonomyObjs']);
		unset($args['_taxObj']);
		unset($args['_metaBoxObj']);
		unset($args['_name']);
		
		return $args;
	}
	
}