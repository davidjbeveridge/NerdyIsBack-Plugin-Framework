<?php
/**
 * @package NIB_View.class.php
 */

/**
 * NerdyIsBack View class
 *
 * Loads templates and populates them with variables.
 *
 * @package NIB_View
 *
 */
class NIB_View	{
	protected $_filepath;
	protected $_data;

	/**
	 * NIB_View constructor
	 *
	 * Sets the filepath and data.
	 *
	 * @param string $filepath
	 * @param array $data
	 */
	public function __construct($filepath,$data=null)	{
		$this->_filepath = $filepath;
		if($data && is_array($data))	{
			$this->_data = $data;
		}
	}

	/**
	 * Populates the method scope with the data and includes the template.
	 *
	 * @param boolean $echo Should output be echoed or returned?
	 * @return mixed $output
	 */
	public function render($echo=FALSE)	{
		if(!$echo)	{
			ob_start();
		}

		if($this->_data)	{
			extract($this->_data);
		}
		include($this->_filepath);

		if(!$echo)	{
			return ob_get_clean();
		}
	}
}