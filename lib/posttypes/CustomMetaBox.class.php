<?php
require_once('FormTablePresenter.class.php');

if(!class_exists('CustomMetaBox'))	{
	/**
	 * CustomMetaBox class
	 *
	 * Implements WordPress's custom meta boxes as objects that are capable
	 * of self-registering with a given post type.  Designed to integrate with
	 * the CustomPostType class, but can be used without it via register()
	 *
	 * @package CustomMetaBox
	 * @version 0.2
	 * @author David Beveridge <davidjbeveridge@gmail.com>
	 *
	 */
	class CustomMetaBox {

		/**
		 * @var string The ID string for the meta box.
		 */
		protected $id;

		/**
		 * @var string The Meta box's title.  Appears at the top of the Meta Box on the edit page.
		 */
		protected $title;

		/**
		 * @var array An array of post types with which the meta box will register.
		 */
		protected $postTypes;

		/**
		 * @var string The part of the edit page where the meta box will appear.  Valid values include 'normal','advanced', or 'side'
		 */
		protected $context = 'normal';

		/**
		 * @var string The priority within the contex where the boxes should show.  Valid options include 'high' or 'low'
		 */
		protected $priority = 'high';

		/**
		 * @var array A multi-dimensional associative array of fields.  Each field must contain values for, at least, id and type.  Most (if not all) should contain more fields.
		 */
		protected $fields = array();

		/**
		 * @var formTablePresenter Presenter object for generating the form
		 */
		protected $_presenter;

	    /**
	     *
	     * @param string $id ID/slug
	     * @param string $title Title to display
	     * @param array $postTypes Array of post type slugs to associate with.
	     * @param string $context Default is 'normal'
	     * @param string $priority Default is 'high'
	     * @param array $fields Array of fields, corresponding to the fields used by FormTablePresenter.
	     */
		public function __construct($id,$title,$postTypes=array(),$fields=null) {
	    	$this->id = $id;
			$this->title = $title;
			if(isset($postTypes) && is_array($postTypes))	{
				$this->postTypes = $postTypes;
			}
			else	{
				$this->postTypes = array();
			}
			if(!is_null($fields))	{
				$this->fields = $fields;
			}

			add_action('save_post',array(&$this,'saveData'));
			add_action('admin_menu',array(&$this,'registerMetaBox'));

			add_action('admin_init',array(&$this,'loadThickBox'));
	    }

	    /**
	     * Generates the Meta Box's internal code.  Uses a FormTablePresenter object.
	     * Should not be called externally, but must be public in order to be accessible to
	     * the WordPress Plugin API.
	    */
	    public function display()	{
	    	if(!is_null($this->_presenter))	{
				$this->_nonce();	//Generate nonce for form authentication
				$this->_presenter->printOutput();
	    	}
		}

		/**
		 * Saves the meta box's information using WordPress' custom fields.
	     * Should not be called externally, but must be public in order to be accessible to
	     * the WordPress Plugin API.
	     *
		 * @param int $postID The ID of the post with which to associate data.
		 */
		public function saveData($postID)	{
			// Verify nonce
			if(@!wp_verify_nonce($_POST[$this->id.'_meta_box_nonce'], basename(__FILE__)))	{
				return $postID;
			}
			// Don't save if this is an autosave.
			if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)	{
				return $postID;
			}

			// Permissions check:

			// Check page permissions
			if('page' == $_POST['post_type'])	{
				if(!current_user_can('edit_page',$postID))	{
					return $postID;
				}
			}
			//Check post permissions
			elseif(!current_user_can('edit_post',$postID))	{
				return $postID;
			}

			// Update each field, one at a time.
			foreach($this->fields as $field)	{
				$oldValue = get_post_meta($postID,$field['id'],TRUE);
				@$newValue = $_POST[$field['id']];
				if($newValue && $newValue != $oldValue)	{
					update_post_meta($postID,$field['id'],$newValue);
				}
				elseif('' == $newValue && $oldValue)	{
					delete_post_meta($postID,$field['id'],$oldValue);
				}
			}
		}

		/**
		 * Adds a post type with which to register on init.
		 *
		 * @param string $postType The post type's slug.
		 * @return CustomMetaBox $this Enables chaining.
		 */
		public function &addPostType($postType)	{
			if(!in_array($postType,$this->postTypes))	{
				$this->postTypes[] = $postType;
			}
			return $this;
		}

		/**
		 * Removes an associated post type by slug.
		 * @param string $postType The post type's slug.
		 * @return CustomMetaBox $this Enables chaining.
		 */
		public function &removePostType($postType)	{
			if(($key = array_search($postType,$this->postTypes)) !== FALSE)	{
				unset($this->postTypes[$key]);
			}
			return $this;
		}

		/**
		 * Registers the Meta Box with the WordPress Plugin API.  Should not be called externally,
		 * but must be public to be accessible to the API.
		 */
		public function registerMetaBox()	{
			foreach($this->postTypes as $postType)	{
				add_meta_box(
					$this->id,					//id
					$this->title,				//title
					array(&$this,'display'),	//callback
					$postType,					//page
					$this->context,				//context
					$this->priority				//priority
				);
			}
		}

		/**
		 * Loads the Thickbox JavaScript library for the 'image' input type.  Should not be called
		 * externally, but must be public to be accessible to the WordPress Plugin API.
		 */
		public function loadThickbox()	{
			wp_enqueue_script('thickbox');
			wp_enqueue_style('thickbox');
		}
		
		public function addField($field)	{
			$this->fields[] = $field;
		}
		
		public function setContext($context)	{
			$this->context = $context;
		}
		public function setPriority($priority)	{
			$this->priority = $priority;
		}
		
		public function setPresenter(&$presenter)	{
			$this->_presenter =& $presenter;
		}

		/**
		 * Simple nonce generator for WordPress' form validation system.
		 */
		protected function _nonce()	{
			echo '<input type="hidden" name="'.$this->id.'_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';
		}

	}
}