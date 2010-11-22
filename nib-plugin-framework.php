<?php
/*
Plugin Name: NerdyIsBack Plugin Framework
Plugin URI: #
Description: Shared libraries for other NerdyIsBack projects.  This plugin does nothing by itself, it just provides some classes for other plugins.
Version: 0.3
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

$libs = glob(dirname(__FILE__).'/libs/*.php');
foreach($libs as $lib)	{
	require_once($lib);
}

require_once(dirname(__FILE__).'/libs/base/NIB_Config.class.php');
require_once(dirname(__FILE__).'/libs/base/NIB_Plugin.class.php');
