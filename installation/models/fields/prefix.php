<?php
/**
 * @version		$Id: prefix.php 21718 2011-07-01 07:52:13Z chdemko $
 * @package		Joomla.Framework
 * @subpackage	Form
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

/**
 * Form Field class for the Joomla Framework.
 *
 * @package		Joomla.Framework
 * @subpackage	Form
 * @since		1.6
 */
class JFormFieldPrefix extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	protected $type = 'Prefix';

	/**
	 * Method to get the field input markup.
	 *
	 * @return	string	The field input markup.
	 * @since	1.6
	 */
	protected function getInput()
	{
		// Initialize some field attributes.
		$size		= $this->element['size'] ? abs((int) $this->element['size']) : 5;
		$count		= $this->element['count'] ? abs((int) $this->element['count']) : 100;
		$maxLength	= $this->element['maxlength'] ? ' maxlength="'.(int) $this->element['maxlength'].'"' : '';
		$class		= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$readonly	= ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
		$disabled	= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';

		// Make sure somebody doesn't put in a too large prefix size value:
		if ($size > 10) {
			$size = 10;
		}

		// If a prefix is already set, use it instead
		$session = JFactory::getSession()->get('setup.options', array());
		if(empty($session['db_prefix'])){

			// Get all tables from this DB
			$tables = JFactory::getDbo()->getTableList();

			// Loop until an non used prefix is found or until $count is reached
			$k = 0;
			do {
				$k++;
				// Create the random prefix:
				$prefix = '';
				$chars = range('a', 'z');
				$numbers = range(0, 9);

				// We want the fist character to be a random letter:
				shuffle($chars);
				$prefix .= $chars[0];

				// Next we combine the numbers and characters to get the other characters:
				$symbols = array_merge($numbers, $chars);
				shuffle($symbols);

				for($i = 0, $j = $size - 1; $i < $j; ++$i) {
					$prefix .= $symbols[$i];
				}

				// Add in the underscore:
				$prefix .= '_';

				// Search for conflict
				$found = false;
				if ($tables) {
					foreach ($tables as $table) {
						if (strpos($table, $prefix) === 0) {
							$found = true;
							break;
						}
					}
				}
			}
			while ($found && $k < $count);
			if ($found) {
				$prefix = '';
			}
		}
		else {
			$prefix = $session['db_prefix'];
		}

		// Initialize JavaScript field attributes.
		$onchange	= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';

		return '<input type="text" name="'.$this->name.'" id="'.$this->id.'"' .
				' value="'.htmlspecialchars($prefix, ENT_COMPAT, 'UTF-8').'"' .
				$class.$disabled.$readonly.$onchange.$maxLength.'/>';
	}
}

