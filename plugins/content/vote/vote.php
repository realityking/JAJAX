<?php
/**
 * @version		$Id: vote.php 21097 2011-04-07 15:38:03Z dextercowley $
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

/**
 * Vote plugin.
 *
 * @package		Joomla.Plugin
 * @subpackage	Content.vote
 */
class plgContentVote extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       1.5
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	* @since	1.6
	*/
	public function onContentBeforeDisplay($context, &$row, &$params, $page=0)
	{
		$html = '';

		if ($params->get('show_vote')) {
			$rating = intval(@$row->rating);
			$rating_count = intval(@$row->rating_count);

			$view = JRequest::getString('view', '');
			$img = '';
			$buttons = '';

			// look for images in template if available
			$starImageOn = JHtml::_('image','system/rating_star.png', NULL, NULL, true);
			$starImageOff = JHtml::_('image','system/rating_star_blank.png', NULL, NULL, true);

			for ($i=0; $i < $rating; $i++) {
				$img .= $starImageOn;
				$value = $i+1;
				$buttons .= '<input type="submit" title="'.JText::sprintf('PLG_VOTE_VOTE', $value).'" name="user_rating" class="vote-button star-on" value="'.$value.'" />';
			}
			for ($i=$rating; $i < 5; $i++) {
				$img .= $starImageOff;
				$value = $i+1;
				$buttons .= '<input type="submit" title="'.JText::sprintf('PLG_VOTE_VOTE', $value).'" name="user_rating" class="vote-button star-off" value="'.$value.'" />';
			}

			if ($view == 'article' && $row->state == 1) {
				JFactory::getDocument()->addScript(JURI::base().'/media/plg_content_vote/vote.js');
				$uri = JFactory::getURI();
				$uri->setQuery($uri->getQuery().'&hitcount=0');

				$html .= '<form method="post" action="' . $uri->toString() . '">';
				$html .= '<div id="content-vote">';
				$html .= '<span id="content-rating">';
				$html .= JText::sprintf('PLG_VOTE_USER_RATING', $buttons, '<span id="rating-count">'.$rating_count.'</span>');
				$html .= '</span>';
				$html .= '<input type="hidden" name="task" value="article.vote" />';
				$html .= '<input type="hidden" name="hitcount" value="0" />';
				$html .= '<input type="hidden" name="url" value="'.  $uri->toString() .'" />';
				$html .= JHtml::_('form.token');
				$html .= '</div>';
				$html .= '</form>';
			} else {
				$html .= '<span class="content_rating">';
				$html .= JText::sprintf('PLG_VOTE_USER_RATING', $img,  $rating_count);
				$html .= '</span>';
			}
		}

		return $html;
	}
}
