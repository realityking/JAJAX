<?php
/**
 * @version		$Id: install.php 20196 2011-01-09 02:40:25Z ian $
 * @package		Joomla.Administrator
 * @subpackage	com_installer
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * @package		Joomla.Administrator
 * @subpackage	com_installer
 */
class InstallerControllerInstall extends JController
{
	/**
	 * Install an extension.
	 *
	 * @return	void
	 * @since	1.8
	 */
	public function install()
	{
		// Check for request forgeries
		JRequest::checkToken() or $this->sendResponse(new Exception(JText::_('JINVALID_TOKEN'), 403));

		$app = JFactory::getApplication();
		$model = $this->getModel('install');

		$r = new JObject();
		if ($model->install()) {
			$cache = JFactory::getCache('mod_menu');
			$cache->clean();
			// TODO: Reset the users acl here as well to kill off any missing bits

			$r->success = true;
			$r->redirect = $app->getUserState('com_installer.redirect_url');
		} else {
			$r->success = false;
			$r->redirect = $app->getUserState('com_installer.redirect_url');
		}
		
		if (!$r->redirect) {
			$r->message = $app->getUserState('com_installer.message');
			$r->extensionmessage = $app->getUserState('com_installer.extension_message');

			// wipe out the user state
			$app->setUserState('com_installer.message', '');
			$app->setUserState('com_installer.extension_message', '');
		}

		// wipe out the redicrect url
		$app->setUserState('com_installer.redirect_url', '');

		$this->sendJsonResponse($r);
	}
}
