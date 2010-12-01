<?php
/**
 * @package NIB_Exception.class.php
 */

/**
 * NIB_Exception class
 *
 * Extends Exception to redefine the __toString method to work better with WordPress.
 *
 * @package NIB_Exception
 *
 */
class NIB_Exception extends Exception	{

	public function __toString()	{
		wp_die($this->getMessage().'<br /><pre>'.$this->getTraceAsString().'</pre>', 'WP-LESS exception');
	}
}