<?php
if(!class_exists('FormTablePresenter'))	{
	/**
	 * Presenter object for HTML forms in a table layout.  Accepts fields strung together as an array of
	 * associative arrays.  Eg.
	 *
	 * array(
	 * 	array('type'=>'heading', 'name'=>'My Heading'),
	 * 	array('type'=>'text', 'id'=>'my_text_input', 'name'=>'My Text input', 'desc'=>'A short description')
	 * )
	 *
	 * @package FormTablePresenter
	 * @version 0.1
	 * @author David Beveridge <davidjbeveridge@gmail.com>
	 */
	class FormTablePresenter	{


		private $_fields;
		private $_output;
		private $_optionType;
		private $_tableClass;

		/**
		 * Constructor for FormTablePresenter class.
		 * @param array $fields
		 * @param string $option_type May be 'post' for a post type or 'option' for options pages. Default is 'post'.
		 */
		public function __construct(&$fields,$option_type = 'post',$tableClass='form-table')	{
			$this->_fields = &$fields;
			$this->_output = '';
			$this->_optionType = $option_type;
			$this->_tableClass = $tableClass;
		}

		/**
		 * Adds a new field.
		 * @param array $field The field to be added, as an associative array.
		 * @return FormTablePresenter $this Enables chaining.
		 */
		public function &addField($field)	{
			$this->_fields[] = $field;
			return $this;
		}

		/**
		 * Generates the form's HTML output (internally), but does not return or output it.
		 * Can be used to refresh the output, if necessary.
		 * @return FormTablePresenter $this Enables chaining.
		 */
		public function &generateOutput()	{
			$this->_output = '<table class="'.$this->_tableClass.'">';

			//Loop through each field; if an appropriate presenter function is found, use it to print output.
			foreach($this->_fields as $field)	{
				if(isset($field['type']) && method_exists($this, '_'.$field['type']))	{
					$this->_output .= $this->{'_'.$field['type']}($field);
				}
			}

			$this->_output .= '</table>';
			return $this;
		}

		/**
		 * Returns the HTML output.
		 */
		public function getOutput()	{
			$this->generateOutput();
			return $this->_output;
		}

		/**
		 * Prints the HTML output.
		 *
		 * @return FormTablePresenter $this Enables chaining.
		 */
		public function &printOutput()	{
			echo $this->getOutput();
			return $this;
		}

		/**
		 * Returns the stored value for a given option by ID.
		 * @param string $optID The ID of an option.
		 * @return mixed $value
		 */
		public function getOption($optID)	{
			switch($this->_optionType)	{
				case 'post':
					global $post;
					return get_post_meta($post->ID,$optID,TRUE);
					break;
				case 'option':
				default:
					return get_option($optID);
			}
		}

		/*
		 * Begin Output Functions
		 */
		private function _text($field)	{
			$out = '<tr><th>';
			$out .= '<label for="'.$field['id'].'">'.htmlentities($field['name']).'</label>';
			$out .= '</th><td>';
			$out .= '<input type="text" class="regular-text" id="'.$field['id'].'" name="'.htmlentities($field['id']).'" value="'.(($inputValue = $this->getOption($field['id'])) ? $inputValue : '').'" />';
			if(isset($field['desc']) && !empty($field['desc']))	{
				$out .= '<br /><span class="description">'.$field['desc'].'</span>';
			}
			$out .= '</td></tr>';
			return $out;
		}
		private function _password($field)	{
			$out = '<tr><th>';
			$out .= '<label for="'.$field['id'].'">'.htmlentities($field['name']).'</label>';
			$out .= '</th><td>';
			$out .= '<input type="password" id="'.$field['id'].'" name="'.htmlentities($field['id']).'" value="'.(($inputValue = $this->getOption($field['id'])) ? $inputValue : '').'" />';
			if(isset($field['desc']) && !empty($field['desc']))	{
				$out .= '<br /><span class="description">'.$field['desc'].'</span>';
			}
			$out .= '</td></tr>';
			return $out;
		}
		private function _textarea($field)	{
			$out = '<tr><th>';
			$out .= '<label for="'.$field['id'].'">'.htmlentities($field['name']).'</label>';
			$out .= '</th><td>';
			$out .= '<textarea cols="50" rows="10" class="regular-text" id="'.$field['id'].'" name="'.htmlentities($field['id']).'">'.(($inputValue = $this->getOption($field['id'])) ? $inputValue : '').'</textarea>';
			if(isset($field['desc']) && !empty($field['desc']))	{
				$out .= '<br /><span class="description">'.$field['desc'].'</span>';
			}
			$out .= '</td></tr>';
			return $out;
		}
		private function _hidden($field)	{
			$out = '<tr><td></th><td>';
			$out .= '<input type="hidden" id="'.$field['id'].'" name="'.htmlentities($field['id']).'" value="'.(($inputValue = $this->getOption($field['id'])) ? $inputValue : '').'" />';
			$out .= '</td></tr>';
			return $out;
		}
		private function _radio($field)	{
			$out = '<tr><th>';
			$out .= '<label for="'.$field['id'].'">'.htmlentities($field['name']).'</label>';
			$out .= '</th><td>';
			$i = 0;
			$currentValue = $this->getOption($field['id']);
			foreach($field['options'] as $rbName => $rbValue)	{
				$out .= '<label for="'.$field['id'].++$i.'">';
				$out .= '<input type="radio" name="'.$field['id'].'" id="'.$field['id'].$i.'" value="'.$rbValue.'"';
				if($rbValue == $currentValue)	{
					$out .= ' checked="checked"';
				}
				$out .= ' />';
				$out .= $rbName;
				$out .= (isset($rb['desc']) ? '<span class="description">'.$rb['desc'].'</span>' : '').'
				</label><br />';
			}
			if(isset($field['desc']))	{
				$out .= '<span class="description">'.$field['desc'].'</span>';
			}
			$out .= '</td></tr>';
			return $out;
		}
		private function _select($field)	{
			$out = '<tr><th>';
			$out .= '<label for="'.$field['id'].'">'.htmlentities($field['name']).'</label>';
			$out .= '</th><td>';
			$i = 0;
			$currentValue = $this->getOption($field['id']);
			$out .= '<select name="'.$field['id'].'">';
			foreach($field['options'] as $rbName => $rbValue)	{
				$out .= '<option value="'.$rbValue.'"';
				if($rbValue == $currentValue)	{
					$out .= ' selected="selected"';
				}
				$out .= '>';
				$out .= $rbName;
				$out .= "</option>";
			}
			$out .= '</select>';
			if(isset($field['desc']))	{
				$out .= '<span class="description">'.$field['desc'].'</span>';
			}
			$out .= '</td></tr>';
			return $out;
		}
		private function _checkbox($field)	{
			$out = '<tr><th></th><td>';
			$out .= '<label for="'.$field['id'].'"><input type="checkbox" id="'.$field['id'].'" name="'.$field['id'].'"'.($this->getOption($field['id']) ? ' checked="checked"' : '').(isset($field['value']) ? ' value="'.$field['value'].'"' : '').' />'.$field['name'].'</label>';
			if(isset($field['desc']))	{
				$out .= '<br /><span class="description">'.$field['desc'].'</span>';
			}
			$out .= '</td></tr>';
			return $out;
		}
		private function _image($field)	{
			$out = '<tr><th>';
			$out .= '<label for="'.$field['id'].'">'.htmlentities($field['name']).'</label>';
			$out .= '</th><td>';
			$out .= '<img id="'.$field['id'].'-image" style="width: 100px;float: left; border: solid 1px #ccc; margin-right: 10px;" src="'.(($inputValue = $this->getOption($field['id'])) ? $inputValue : get_bloginfo('template_url').'/images/no-image-100.png').'" />';
			$out .= '<input type="hidden" id="'.$field['id'].'" name="'.$field['id'].'" value="'.(($inputValue = $this->getOption($field['id'])) ? $inputValue : '').'" />';
			if(isset($field['desc']) && !empty($field['desc']))	{
				$out .= '<span class="description">'.$field['desc'].'</span>';
			}
			$out .= '<br /><input type="button" value="Select Image" class="button-primary" id="'.$field['id'].'-button" />';
			$out .= '
				<script type="text/javascript" />//<![CDATA[
					(function($){
						$(window).load(function(){
							$("#'.$field['id'].'-button").click(function(e){
								window.send_to_editor = function(html){
									var imgUrl = $("img",html).attr("src");
									$("#'.$field['id'].'").val(imgUrl)
									$("#'.$field['id'].'-image").attr("src",imgUrl);
									tb_remove();
								};
								tb_show("","'.get_bloginfo('wpurl').'/wp-admin/media-upload.php?post_id=0&type=image&TB_iframe=true");
								return false;
							});
						});
					})(jQuery)
					//]]>
				</script>';
			$out .= '</td></tr>';
			return $out;
		}
		private function _heading($field)	{
			return '<tr><td colspan="2"><h4>'.$field['name'].'</h4></td></tr>';
		}
		private function _subheading($field)	{
			return '<tr><td colspan="2"><h5>'.$field['name'].'</h5></td></tr>';
		}
		private function _paragraph($field)	{
			return '<tr><td colspan="2"><p>'.$field['name'].'</p></td></tr>';
		}
	}
}