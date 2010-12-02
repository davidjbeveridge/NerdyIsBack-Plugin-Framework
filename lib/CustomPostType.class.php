<?php
/**
 * Creates new custom post types in WordPress using register_post_type();
 * Integrates with CustomMetaBox and CustomTaxonomy.
 *
 * @package CustomPostType
 * @version 0.2
 * @author David Beveridge <davidjbeveridge@gmail.com>
 */
class CustomPostType	{

	private $_type;
	private $_args;
	private $_metaBoxes;
	private $_taxonomies;

	/**
	 * CustomPostType constructor.  Takes the same arguments as WordPress' {@link http://codex.wordpress.org/Function_Reference/register_post_type register_post_type()} function.
	 *
	 * @param string $type The post type slug
	 * @param array $args Arguments for the post type.  See {@link http://codex.wordpress.org/Function_Reference/register_post_type#Arguments register_post_type()}.
	 */
	public function __construct($type,$args)	{
		$this->_type = $type;
		$this->_args = $args;
		$this->_metaBoxes = array();
		$this->_taxonomies = array();

		//Add action to register post type
		add_action('init',array(&$this,'register'));

		//Add action to look for and include template file for this type, if found.
		add_action('template_redirect',array(&$this,'doTemplateRedirect'));

	}

	/**
	 * Registers the post type with WordPress.
	 *
	 * register() does not need to be called directly.  It is set public so that WordPress
	 * is able to execute it.
	 */
	public function register()	{
		register_post_type($this->_type,$this->_args);
	}

	/**
	 * Registers the template redirection for the post type based on the slug name.  For example,
	 * if your post type's slug is 'book' the template file will be book.php
	 *
	 * doTemplateRedirect() does not need to be called directly.  It is set public so that WordPress
	 * is able to execute it.
	 */
	public function doTemplateRedirect()	{
		global $wp;
		if(@$wp->query_vars['post_type'] == $this->_type && file_exists(TEMPLATEPATH."/{$this->_type}.php"))	{
			include(TEMPLATEPATH."/{$this->_type}.php");
			exit();
		}
	}

	/**
	 * Adds a CustomMetaBox object to be registered to this post type.
	 *
	 * @param CustomMetaBox $metaBox
	 * @return CustomPostType $this Enables chaining.
	 */
	public function &addMetaBox(&$metaBox)	{
		if(is_object($metaBox) && 'CustomMetaBox' == get_class($metaBox))	{
			$metaBox->addPostType($this->_type);
			if(!in_array($metaBox,$this->_metaBoxes))	{
				$this->_metaBoxes[] = $metaBox;
			}
		}
		return $this;
	}

	/**
	 * Removes a CustomMetaBox object that has been registered to this post type.
	 *
	 * @param CustomMetaBox $metaBox
	 * @return CustomPostType $this Enables chaining.
	 */
	public function &removeMetaBox(&$metaBox)	{
		$metaBox->removePostType($this->_type);
		if(($key = array_search($metaBox,$this->_metaBoxes)) !== FALSE)	{
			unset($this->_metaBoxes[$key]);
		}
		return $this;
	}

	/**
	 * Adds a CustomTaxonomy object to be registered to this post type.
	 *
	 * @param CustomPostType $taxonomy
	 * @return CustomPostType $this Enables chaining.
	 */
	public function &addTaxonomy(&$taxonomy)	{
		if(is_object($taxonomy) && 'CustomTaxonomy' == get_class($taxonomy))	{
			$taxonomy->addPostType($this->_type);
			if(!in_array($taxonomy,$this->_taxonomies))	{
				$this->_taxonomies[] = $taxonomy;
			}
		}
		return $this;
	}

	/**
	 * Removes a CustomTaxonomy object that has been registered to this post type.
	 *
	 * @param CustomTaxonomy $taxonomy
	 * @return CustomPostType $this Enables chaining.
	 */
	public function &removeTaxonomy(&$taxonomy)	{
		$taxonomy->removePostType($this->_type);
		if(($key = array_search($taxonomy,$this->_taxonomies)) !== FALSE)	{
			unset($this->_taxonomies[$key]);
		}
		return $this;
	}

}
