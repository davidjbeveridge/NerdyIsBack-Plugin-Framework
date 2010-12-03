<?php

if(!class_exists('CustomTaxonomy'))	{

	/**
	 * CustomTaxonomy class
	 *
	 * Creates custom taxonomies designed to integrate with the CustomPostType class.
	 * Custom taxonomies should self-register with a given post type through the
	 * addPostType() and registerTaxonomy() functions.
	 *
	 * @package CustomTaxonomy
	 * @version 0.1
	 * @author David Beveridge <davidjbeveridge@gmail.com>
	 *
	 */
	class CustomTaxonomy {

		private $_name;
		private $_postTypes;
		private $_args;

	    /**
	     * CustomTaxonomy constructor.  Accepts the same arguments as {@link http://codex.wordpress.org/Function_Reference/register_taxonomy register_taxonomy()}.
	     *
	     * @param string $name
	     * @param array $args
	     */
		public function __construct($name,$args) {
	    	$this->_name = $name;
	    	$this->_args = $args;

	    	$this->_postTypes = array();

	    	//Add action for registering the taxonomy
	    	add_action('init',array(&$this,'registerTaxonomy'));
	    }

	    /**
	     * Registers the CustomTaxonomy object with all associated post types.  registerTaxonomy() should
	     * not be called externally as it is registered as an action hook by the constructor.
	     */
	    public function registerTaxonomy()	{
	    	if(!empty($this->_postTypes) && count($this->_postTypes))	{
	    		$firstPostType = array_shift($this->_postTypes);
	    		register_taxonomy($this->_name,$firstPostType,$this->_args);
	    		foreach($this->_postTypes as $postType)	{
	    			register_taxonomy_for_object_type($this->_name,$postType);
	    		}
	    		array_unshift($this->_postTypes, $firstPostType);
	    	}
	    }

	    /**
	     * Adds a post type slug to be registered by registerTaxonomy.  Generally called by
	     * CustomPostType.
	     *
	     * @param string $postType The post type slug
	     * @return CustomTaxonomy $this Enables chaining.
	     */
		public function &addPostType($postType)	{
			if(!in_array($postType,$this->_postTypes))	{
				$this->_postTypes[] = $postType;
			}
			return $this;
		}

		/**
		 * Removes a previously registered post type (by slug).
		 *
		 * @param string $postType The post type slug.
		 * @return CustomTaxonomy $this Enables chaining.
		 */
		public function &removePostType($postType)	{
			if(($key = array_search($postType,$this->_postTypes)) !== FALSE)	{
				unset($this->_postTypes[$key]);
			}
		}

	}
}