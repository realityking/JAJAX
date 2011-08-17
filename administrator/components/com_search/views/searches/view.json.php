<?php
/**
 * @version		$Id: view.html.php 20196 2011-01-09 02:40:25Z ian $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of search terms.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_search
 * @since		1.8
 */
class SearchViewSearches extends JView
{
	protected $enabled;
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		$this->enabled		= $this->state->params->get('enabled');

		// Check for errors. @todo this has to be queed up and converted to JSON
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$r = new JObject;
		$r->token = JUtility::getToken(true);
		$r->items = array();
		foreach ($this->items as $i => $item) {
			$rItem = new JObject;
			$rItem->index = $i + 1 + $this->pagination->limitstart;
			$rItem->search_term = $item->search_term;
			if ($this->state->get('filter.results')) {
				$rItem->results = (int) $item->returns;
			} else {
				$rItem->results = JText::_('COM_SEARCH_NO_RESULTS');
			}
			$r->items[$i] = $rItem;
		}
		
		echo json_encode($r);
	}
}