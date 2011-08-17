<?php
/**
 * @version		$Id: view.html.php 20196 2011-01-09 02:40:25Z ian $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * JSON View class for the Login component
 *
 * @package		Joomla.Administrator
 * @subpackage	com_login
 * @since		1.8
 */
class LoginViewLogin extends JView
{
	function display($tpl=null)
	{
		$r = new JObject();
		$r->token = JUtility::getToken(true);
		
		echo json_encode($r);
	}
}
