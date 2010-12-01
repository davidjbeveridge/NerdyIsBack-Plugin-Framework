<?php
/*
Plugin Name: NerdyIsBack Plugin Framework
Plugin URI: #
Description: Shared libraries for other NerdyIsBack projects.  This plugin does nothing by itself, it just provides some classes for other plugins.
Version: 0.4
Author: David Beveridge
Author URI: http://www.nerdyisback.com
License: MIT
*/
/*	Copyright (c) 2010 David Beveridge, Studio DBC

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.
*/

// nib.php is necessary to implement the NerdyIsBack MVC Component.
/**
 * If your permissions are set tightly, WordPress won't be able to copy
 * nib.php into the wp-admin/ directory; you will have to copy it manually
 * for the NerdyIsBack Plugin Framework to operate properly.
 */
if(!file_exists(ABSPATH.'/wp-admin/nib.php'))	{
	@copy(dirname(__FILE__).'/nib.php',ABSPATH.'/wp-admin/nib.php');
}

require_once(dirname(__FILE__).'/lib/base/NIB_Exception.class.php');
require_once(dirname(__FILE__).'/lib/base/NIB_Controller.class.php');
require_once(dirname(__FILE__).'/lib/base/NIB_Model.class.php');
require_once(dirname(__FILE__).'/lib/base/NIB_View.class.php');
require_once(dirname(__FILE__).'/lib/base/NIB_CompositeView.class.php');
require_once(dirname(__FILE__).'/lib/base/NIB_WordPress_Menu.class.php');
require_once(dirname(__FILE__).'/lib/base/NIB_Plugin.class.php');
