<?php
require_once('FormTablePresenter.class.php');

if(!class_exists('CustomOptionsPage'))	{
	/**
	 *
	 * @author Dave
	 *
	 */
	class CustomOptionsPage {
		private $_topMenu;
		private $_id;
		private $_name;
		private $_permission;
		private $_options;
		private $_presenter;

		/**
		 *
		 * @param string $topMenu The slug or filename of the parent menu
		 * @param string $id Menu slug
		 * @param string $name Name that will appear in the menu
		 * @param string $permission Required WordPress permission; default is manage_options
		 * @param array $fields Fields for the menu page.  Should fit the same format accepted by FormTablePresenter.
		 */
		public function __construct($topMenu,$id,$name,$permission='manage_options',$fields=array())	{
			$this->_topMenu = $topMenu;
			$this->_id = $id;
			$this->_name = $name;
			$this->_permission = $permission;
			$this->_options = array();
			foreach($fields as $key => $field)	{
				@$field['id'] = $this->_id.'_'.$field['id'];
				$this->_options[$field['id']] = $field;
			}
			add_action('admin_menu',array(&$this,'register'));
		}

		private function _handleForm()	{
			if(isset($_REQUEST['save']))	{
				$this->_saveForm();
			}
			elseif(isset($_REQUEST['reset']))	{
				$this->_resetForm();
			}
		}

		private function _saveForm()	{
			if(@!wp_verify_nonce($_POST[$this->id.'_option_nonce'], basename(__FILE__)))	{
				return -1;
			}
			if(!current_user_can($this->_permission))	{
				return -1;
			}

			foreach($this->_options as $option)	{
				$default = isset($option['default']) ? $option['default'] : NULL;
				$oldValue = get_option($option['id'],$default);
				@$newValue = $_POST[$option['id']];
				if($newValue && $newValue != $oldValue)	{
					update_option($option['id'],$newValue);
				}
				elseif('' == $newValue && $oldValue)	{
					delete_option($option['id']);
				}
			}
		}

		private function _resetForm()	{
			foreach($this->_options as $option)	{
				if($option['type'] != 'title' && $option['type'] != 'subtitle')	{
					if(isset($option['default']))	{
						update_option($option['id'],$option['default']);
					}
					else	{
						delete_option($option['id']);
					}
				}
			}
		}

		private function _getForm()	{
			$presenter = new FormTablePresenter($this->_options,'option');
			$output = '
			<div class="icon32" id="icon-options-general"><br></div>
			<div class="wrap">
			<h2>'.$this->_name.' Options</h2>
				<form enctype="multipart/form-data" method="post" action="http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'">';
			$output .= $presenter->getOutput();
			$output .= '
					<p>
						<input type="submit" name="save" value="Save Options" class="button-primary" />
						<input type="submit" name="reset" value="Reset Defaults" class="button-secondary" />
					</p>
					<input type="hidden" name="'.$this->id.'_option_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />
				</form>
			</div>';
			return $output;
		}

		private function _unescape($string)	{
			if(get_magic_quotes_gpc() OR get_magic_quotes_runtime())	{
				return stripslashes( stripslashes( esc_attr( $string ) ) );
			}
			return stripslashes( esc_attr( $string ) );
		}

		/**
		 * Registers the options page with the WordPress Plugins API.  Should not be called externally,
		 * but must be public in order to be accessible to the API.
		 */
		public function register()	{
			if(in_array(strtolower($this->_topMenu),array('dashboard','posts','media','links','pages','comments','theme','plugins','users','management','options')))	{
				call_user_func('add_'.strtolower($this->_topMenu).'_page',__($this->_name),__($this->_name),$this->_permission,$this->_id,array(&$this,'optionsPage'));
			}
			else	{
				add_submenu_page($this->_topMenu,__($this->_name),__($this->_name),$this->_permission,$this->_id,array(&$this,'optionsPage'));
			}
		}

		/**
		 * The callback function passed to the WordPress Plugin API by register().  Should not be called
		 * externally, but must be public to be accessible to the API.
		 */
		public function optionsPage()	{
			$this->_handleForm();
			echo $this->_getForm();
		}

		/**
		 * Gets the value of a given option by ID.
		 * @param string $id The ID string of the option.
		 */
		public function getOption($id)	{
			return get_option($this->_id.'_'.$id);
		}

	}
}
