<?php
/**
 * @version		$Id: article.json.php 20265 2011-01-10 23:49:25Z dextercowley $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');

/**
 * - JSON Protocol -
 * 
 * @package		Joomla.Site
 * @subpackage	com_content
 * @since		1.8
 */
class ContentControllerArticle extends JControllerForm
{
	/**
	 * Method to save a vote.
	 *
	 * @return	void
	 * @since	1.8
	 */
	function vote()
	{
		// Check for request forgeries.
		if !(JRequest::checkToken()) {
			$this->sendJsonResponse(new Exception(JText::_('JINVALID_TOKEN'), 403));
			die();
		}

		$app = JFactory::getApplication();
		$user_rating = JRequest::getInt('user_rating', -1);
		if ($user_rating > -1) {
			$id = JRequest::getInt('id', 0);
			$viewName = JRequest::getString('view', $this->default_view);
			$model = $this->getModel($viewName);

			$r = new JObject();
			$success = $model->storeVote($id, $user_rating);
			$item = &$model->getItem($id);
			$r->rating = intval($item->rating);
			$r->rating_count = intval($item->rating_count);

			if ($success) {
				$app->enqueueMessage(JText::_('COM_CONTENT_ARTICLE_VOTE_SUCCESS'));
			} else {
				$error = JError::getError();
				if (!$error) {
					$app->enqueueMessage(JText::_('COM_CONTENT_ARTICLE_VOTE_FAILURE'));
				}
			}
			$this->sendJsonResponse($r);
		}
	}
}