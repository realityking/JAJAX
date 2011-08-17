<?php
/**
 * JArrayHelperTest
 *
 * @version   $Id: JArrayHelperTest.php 21322 2011-05-11 01:10:29Z dextercowley $
 * @package   Joomla.UnitTest
 * @copyright Copyright (C) 2005 - 2011 Open Source Matters. All rights reserved.
 * @license   GNU General Public License
 */
require_once 'PHPUnit/Framework.php';
require_once JPATH_BASE . '/libraries/joomla/utilities/arrayhelper.php';

/**
 * JArrayHelperTest
 *
 * Test class for JArrayHelper.
 * Generated by PHPUnit on 2009-10-26 at 22:31:37.
 *
 * @package	Joomla.UnitTest
 * @subpackage Utilities
 */
class JArrayHelperTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		jimport('joomla.utilities.string');
	}

	/**
	 * Data provider for numeric inputs
	 *
	 * @return array
	 */
	function getTestToIntegerData()
	{
		return array(
			'floating with single argument' => array(
				array(0.9, 3.2, 4.9999999, 7.5),
				null,
				array(0, 3, 4, 7),
				'Should truncate numbers in array'
			),
			'floating with default array' => array(
				array(0.9, 3.2, 4.9999999, 7.5),
				array(1, 2, 3),
				array(0, 3, 4, 7),
				'Supplied default should not be used'
			),
			'non-array with single argument' => array(
				12,
				null,
				array(),
				'Should replace non-array input with empty array'
			),
			'non-array with default array' => array(
				12,
				array(1.5, 2.6, 3),
				array(1, 2, 3),
				'Should replace non-array input with array of truncated numbers'
			),
			'non-array with default single' => array(
				12,
				3.5,
				array(3),
				'Should replace non-array with single-element array of truncated number'
			),
		);
	}

	/**
	 * Test convert an array to all integers.
	 *
	 * @param string $input   The array being input
	 * @param string $default The default value
	 * @param string $expect  The expected return value
	 * @param string $message The failure message
	 *
	 * @return void
	 * @dataProvider getTestToIntegerData
	 */
	public function testToInteger($input, $default, $expect, $message)
	{
		JArrayHelper::toInteger($input, $default);
		$this->assertEquals($expect, $input, $message);
	}

	/**
	 * Data provider for object inputs
	 *
	 * @return array
	 */
	function getTestToObjectData()
	{
		return array(
			'single object' => array(
				array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				null,
				(object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				'Should turn array into single object'
			),
			'multiple objects' => array(
				array(
					'first' => array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'second' => array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'third' => array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				),
				null,
				(object) array(
					'first' => (object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'second' => (object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'third' => (object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				),
				'Should turn multiple dimension array into nested objects'
			),
			'single object with class' => array(
				array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				'stdClass',
				(object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				'Should turn array into single object'
			),
			'multiple objects with class' => array(
				array(
					'first' => array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'second' => array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'third' => array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				),
				'stdClass',
				(object) array(
					'first' => (object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'second' => (object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'third' => (object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				),
				'Should turn multiple dimension array into nested objects'
			),
		);
	}

	/**
	 * Test convert array to object.
	 *
	 * @param string $input	The array being input
	 * @param string $className The class name to build
	 * @param string $expect	The expected return value
	 * @param string $message   The failure message
	 *
	 * @return void
	 * @dataProvider getTestToObjectData
	 */
	public function testToObject($input, $className, $expect, $message)
	{
		$this->assertEquals(
			$expect,
			JArrayHelper::toObject($input),
			$message
		);
	}

	/**
	 * Data provider for string inputs
	 *
	 * @return array
	 */
	function getTestToStringData()
	{
		return array(
			'single dimension 1' => array(
				array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				null,
				null,
				false,
				'integer="12" float="1.29999" string="A Test String"',
				'Should turn array into single string with defaults',
				true
			),
			'single dimension 2' => array(
				array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				" = ",
				null,
				true,
				'integer = "12"float = "1.29999"string = "A Test String"',
				'Should turn array into single string with " = " and no spaces',
				false
			),
			'single dimension 3' => array(
				array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				' = ',
				' then ',
				true,
				'integer = "12" then float = "1.29999" then string = "A Test String"',
				'Should turn array into single string with " = " and then between elements',
				false
			),
			'multiple dimensions 1' => array(
				array(
					'first' => array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'second' => array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'third' => array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				),
				null,
				null,
				false,
				'integer="12" float="1.29999" string="A Test String" '.
					'integer="12" float="1.29999" string="A Test String" '.
					'integer="12" float="1.29999" string="A Test String"',
				'Should turn multiple dimension array into single string',
				true
			),
			'multiple dimensions 2' => array(
				array(
					'first' => array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'second' => array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'third' => array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				),
				' = ',
				null,
				false,
				'integer = "12"float = "1.29999"string = "A Test String"'.
					'integer = "12"float = "1.29999"string = "A Test String"'.
					'integer = "12"float = "1.29999"string = "A Test String"',
				'Should turn multiple dimension array into single string with " = " and no spaces',
				false
			),
			'multiple dimensions 3' => array(
				array(
					'first' => array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'second' => array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'third' => array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				),
				' = ',
				' ',
				false,
				'integer = "12" float = "1.29999" string = "A Test String" '.
					'integer = "12" float = "1.29999" string = "A Test String" '.
					'integer = "12" float = "1.29999" string = "A Test String"',
				'Should turn multiple dimension array into single string with " = " and a space',
				false
			),
			'multiple dimensions 4' => array(
				array(
					'first' => array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'second' => array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'third' => array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				),
				' = ',
				null,
				true,
				'firstinteger = "12"float = "1.29999"string = "A Test String"'.
					'secondinteger = "12"float = "1.29999"string = "A Test String"'.
					'thirdinteger = "12"float = "1.29999"string = "A Test String"',
				'Should turn multiple dimension array into single string with " = " and no spaces with outer key',
				false
			),
		);
	}

	/**
	 * Tests converting array to string.
	 *
	 * @param array  $input	The array being input
	 * @param string $inner	The inner glue
	 * @param string $outer	The outer glue
	 * @param bool   $keepKey  Keep the outer key
	 * @param string $expect   The expected return value
	 * @param string $message  The failure message
	 * @param bool   $defaults Use function defaults (true) or full argument list
	 *
	 * @return void
	 * @dataProvider getTestToStringData
	 */
	public function testToString(
		$input, $inner, $outer, $keepKey, $expect, $message, $defaults)
	{
		if ($defaults) {
			$output = JArrayHelper::toString($input);
		} else {
			$output = JArrayHelper::toString($input, $inner, $outer, $keepKey);
		}

		$this->assertEquals(
			$expect,
			$output,
			$message
		);
	}

	/**
	 * Data provider for from object inputs
	 *
	 * @return array
	 */
	function getTestFromObjectData()
	{
		return array(
			'single 1' => array(
				(object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				null,
				null,
				array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				'Should turn object into single dimension array',
				true
			),
			'single 2' => array(
				(object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				true,
				null,
				array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				'Should turn object into single dimension array with recursion',
				false
			),
			'single 3' => array(
				(object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				true,
				'/^(integer|float)/',
				array('integer' => 12, 'float' => 1.29999),
				'Should turn object into single dimension array getting only integer and float attribs',
				false
			),
			'single 4' => array(
				(object) array(
					'first' => (object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'second' => (object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'third' => (object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				),
				null,
				null,
				array(
					'first' => (object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'second' => (object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'third' => (object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				),
				'Should turn nested objects into single dimension array',
				false
			),
			'multiple 1' => array(
				(object) array(
					'first' => (object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'second' => (object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'third' => (object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				),
				null,
				null,
				array(
					'first' => array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'second' => array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'third' => array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				),
				'Should turn nested objects into multiple dimension array',
				true
			),
			'multiple 2' => array(
				(object) array(
					'first' => (object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'second' => (object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'third' => (object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				),
				true,
				null,
				array(
					'first' => array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'second' => array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'third' => array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				),
				'Should turn nested objects into multiple dimension array',
				true
			),
			'multiple 3' => array(
				(object) array(
					'first' => (object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'second' => (object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'third' => (object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				),
				false,
				null,
				array(
					'first' => (object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'second' => (object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'third' => (object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				),
				'Should turn nested objects into single dimension array',
				false
			),
			'multiple 4' => array(
				(object) array(
					'first' => 'Me',
					'second' => (object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'third' => (object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				),
				false,
				null,
				array(
					'first' => 'Me',
					'second' => (object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'third' => (object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				),
				'Should turn nested objects into single dimension array',
				false
			),
			'multiple 5' => array(
				(object) array(
					'first' => (object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'second' => (object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					'third' => (object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				),
				true,
				'/(first|second|integer|string)/',
				array(
					'first' => array('integer' => 12, 'string' => 'A Test String'),
					'second' => array('integer' => 12, 'string' => 'A Test String'),
				),
				'Should turn nested objects into multiple dimension array of int and string',
				false
			),
			'multiple 6' => array(
				(object) array(
					'first' => array(
						'integer' => 12,
						'float' => 1.29999,
						'string' => 'A Test String',
						'third' => (object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					),
					'second' => array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				),
				null,
				null,
				array(
					'first' => array(
						'integer' => 12,
						'float' => 1.29999,
						'string' => 'A Test String',
						'third' => array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					),
					'second' => array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				),
				'Should turn nested objects into multiple dimension array',
				true
			),
		);
	}

	/**
	 * Tests conversion of object to string.
	 *
	 * @param array  $input	The array being input
	 * @param bool   $recurse  Recurse through multiple dimensions?
	 * @param string $regex	Regex to select only some attributes
	 * @param string $expect   The expected return value
	 * @param string $message  The failure message
	 * @param bool   $defaults Use function defaults (true) or full argument list
	 *
	 * @return void
	 * @dataProvider getTestFromObjectData
	 */
	public function testFromObject(
		$input, $recurse, $regex, $expect, $message, $defaults)
	{
		if ($defaults) {
			$output = JArrayHelper::fromObject($input);
		}
		else {
			$output = JArrayHelper::fromObject($input, $recurse, $regex);
		}

		$this->assertEquals(
			$expect,
			$output,
			$message
		);
	}

	/**
	 * Data provider for get column
	 *
	 * @return array
	 */
	function getTestGetColumnData()
	{
		return array(
			'generic array' => array(
				array(
					array(1, 2, 3 ,4 ,5),
					array(6, 7, 8, 9, 10),
					array(11, 12, 13, 14, 15),
					array(16, 17, 18, 19, 20)
				),
				2,
				array(3, 8, 13, 18),
				'Should get column #2'
			),
			'associative array' => array(
				array(
					array('one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5),
					array('one' => 6, 'two' => 7, 'three' => 8, 'four' => 9, 'five' => 10),
					array('one' => 11, 'two' => 12, 'three' => 13, 'four' => 14, 'five' => 15),
					array('one' => 16, 'two' => 17, 'three' => 18, 'four' => 19, 'five' => 20)
				),
				'four',
				array(4, 9, 14, 19),
				'Should get column \'four\''
			),
			'object array' => array(
				array(
					(object) array('one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5),
					(object) array('one' => 6, 'two' => 7, 'three' => 8, 'four' => 9, 'five' => 10),
					(object) array('one' => 11, 'two' => 12, 'three' => 13, 'four' => 14, 'five' => 15),
					(object) array('one' => 16, 'two' => 17, 'three' => 18, 'four' => 19, 'five' => 20)
				),
				'four',
				array(4, 9, 14, 19),
				'Should get column \'four\''
			),
		);
	}

	/**
	 * Test pulling data from a single column (by index or association).
	 *
	 * @param array  $input   Input array
	 * @param mixed  $index   Column to pull, either by association or number
	 * @param array  $expect  The expected results
	 * @param string $message The failure message
	 *
	 * @return void
	 * @dataProvider getTestGetColumnData
	 */
	public function testGetColumn($input, $index, $expect, $message)
	{
		$this->assertEquals(
			$expect,
			JArrayHelper::getColumn($input, $index),
			$message
		);
	}

	/**
	 * Data provider for get value
	 *
	 * @return array
	 */
	function getTestGetValueData()
	{
		$input = array('one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5,
					'six' => 6, 'seven' => 7, 'eight' => 8, 'nine' => 'It\'s nine', 'ten' => 10,
					'eleven' => 11, 'twelve' => 12, 'thirteen' => 13, 'fourteen' => 14, 'fifteen' => 15,
					'sixteen' => 16, 'seventeen' => 17, 'eightteen' => 'eighteen ninety-five', 'nineteen' => 19, 'twenty' => 20
				);

		return array(
			'defaults' => array(
				$input,
				'five',
				null,
				null,
				5,
				'Should get 5',
				true
			),
			'get non-value' => array(
				$input,
				'fiveio',
				198,
				null,
				198,
				'Should get the default value',
				false
			),
			'get int 5' => array(
				$input,
				'five',
				198,
				'int',
				(int)5,
				'Should get an int',
				false
			),
			'get float six' => array(
				$input,
				'six',
				198,
				'float',
				(float)6,
				'Should get a float',
				false
			),
			'get get boolean seven' => array(
				$input,
				'seven',
				198,
				'bool',
				(bool)7,
				'Should get a boolean',
				false
			),
			'get array eight' => array(
				$input,
				'eight',
				198,
				'array',
				array(8),
				'Should get an array',
				false
			),
			'get string nine' => array(
				$input,
				'nine',
				198,
				'string',
				'It\'s nine',
				'Should get string',
				false
			),
			'get word' => array(
				$input,
				'eightteen',
				198,
				'word',
				'eighteenninetyfive',
				'Should get it as a single word',
				false
			),
		);
	}

	/**
	 * Test get value from an array.
	 *
	 * @param array  $input	Input array
	 * @param mixed  $index	Element to pull, either by association or number
	 * @param mixed  $default  The defualt value, if element not present
	 * @param $type  $type	The type of value returned
	 * @param array  $expect   The expected results
	 * @param string $message  The failure message
	 * @param bool   $defaults Use the defaults (true) or full argument list
	 *
	 * @return void
	 * @dataProvider getTestGetValueData
	 */
	public function testGetValue(
		$input, $index, $default, $type, $expect, $message, $defaults)
	{
		if ($defaults) {
			$output = JArrayHelper::getValue($input, $index);
		} else {
			$output = JArrayHelper::getValue($input, $index, $default, $type);
		}

		$this->assertEquals(
			$expect,
			$output,
			$message
		);
	}

	/**
	 * Test the JArrayHelper::isAssociate method.
	 */
	public function testIsAssociative()
	{
		$this->assertThat(
			JArrayHelper::isAssociative(array(1,2,3)),
			$this->isFalse(),
			'Line: '.__LINE__.' This array should not be associative.'
		);

		$this->assertThat(
			JArrayHelper::isAssociative(array('a' => 1, 'b' => 2, 'c' => 3)),
			$this->isTrue(),
			'Line: '.__LINE__.' This array should be associative.'
		);

		$this->assertThat(
			JArrayHelper::isAssociative(array('a' => 1, 2, 'c' => 3)),
			$this->isTrue(),
			'Line: '.__LINE__.' This array should be associative.'
		);
	}

	/**
	 * Data provider for sorting objects
	 *
	 * @return array
	 */
	function getTestSortObjectData()
	{
		$input1 = array(
					(object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					(object) array('integer' => 15, 'float' => 1.29999, 'string' => 'C Test String'),
					(object) array('integer' => 35, 'float' => 1.29999, 'string' => 'C Test String'),
					(object) array('integer' => 1, 'float' => 1.29999, 'string' => 'N Test String'),
					(object) array('integer' => 5, 'float' => 1.29999, 'string' => 'T Test String'),
					(object) array('integer' => 22, 'float' => 1.29999, 'string' => 'E Test String'),
					(object) array('integer' => 6, 'float' => 1.29999, 'string' => 'G Test String'),
					(object) array('integer' => 6, 'float' => 1.29999, 'string' => 'L Test String'),
				);
		$input2 = array(
					(object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					(object) array('integer' => 15, 'float' => 1.29999, 'string' => 'C Test String'),
					(object) array('integer' => 35, 'float' => 1.29999, 'string' => 'C Test String'),
					(object) array('integer' => 1, 'float' => 1.29999, 'string' => 'N Test String'),
					(object) array('integer' => 5, 'float' => 1.29999, 'string' => 't Test String'),
					(object) array('integer' => 22, 'float' => 1.29999, 'string' => 'E Test String'),
					(object) array('integer' => 6, 'float' => 1.29999, 'string' => 'g Test String'),
					(object) array('integer' => 6, 'float' => 1.29999, 'string' => 'L Test String'),
				);

		$input3 = array(
					(object) array('string' => 'A Test String', 'integer' => 1, ),
					(object) array('string' => 'é Test String', 'integer' => 2, ),
					(object) array('string' => 'è Test String', 'integer' => 3, ),
					(object) array('string' => 'É Test String', 'integer' => 4, ),
					(object) array('string' => 'È Test String', 'integer' => 5, ),
					(object) array('string' => 'Œ Test String', 'integer' => 6, ),
					(object) array('string' => 'œ Test String', 'integer' => 7, ),
					(object) array('string' => 'L Test String', 'integer' => 8, ),
					(object) array('string' => 'P Test String', 'integer' => 9, ),
					(object) array('string' => 'p Test String', 'integer' => 10, ),
				);



		return array(
			'by int defaults' => array(
				$input1,
				'integer',
				null,
				false,
				false,
				array(
					(object) array('integer' => 1, 'float' => 1.29999, 'string' => 'N Test String'),
					(object) array('integer' => 5, 'float' => 1.29999, 'string' => 'T Test String'),
					(object) array('integer' => 6, 'float' => 1.29999, 'string' => 'G Test String'),
					(object) array('integer' => 6, 'float' => 1.29999, 'string' => 'L Test String'),
					(object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					(object) array('integer' => 15, 'float' => 1.29999, 'string' => 'C Test String'),
					(object) array('integer' => 22, 'float' => 1.29999, 'string' => 'E Test String'),
					(object) array('integer' => 35, 'float' => 1.29999, 'string' => 'C Test String'),
				),
				'Should be sorted by the integer field in ascending order',
				true
			),
			'by int ascending' => array(
				$input1,
				'integer',
				1,
				false,
				false,
				array(
					(object) array('integer' => 1, 'float' => 1.29999, 'string' => 'N Test String'),
					(object) array('integer' => 5, 'float' => 1.29999, 'string' => 'T Test String'),
					(object) array('integer' => 6, 'float' => 1.29999, 'string' => 'G Test String'),
					(object) array('integer' => 6, 'float' => 1.29999, 'string' => 'L Test String'),
					(object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					(object) array('integer' => 15, 'float' => 1.29999, 'string' => 'C Test String'),
					(object) array('integer' => 22, 'float' => 1.29999, 'string' => 'E Test String'),
					(object) array('integer' => 35, 'float' => 1.29999, 'string' => 'C Test String'),
				),
				'Should be sorted by the integer field in ascending order full argument list',
				false
			),
			'by int descending' => array(
				$input1,
				'integer',
				-1,
				false,
				false,
				array(
					(object) array('integer' => 35, 'float' => 1.29999, 'string' => 'C Test String'),
					(object) array('integer' => 22, 'float' => 1.29999, 'string' => 'E Test String'),
					(object) array('integer' => 15, 'float' => 1.29999, 'string' => 'C Test String'),
					(object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					(object) array('integer' => 6, 'float' => 1.29999, 'string' => 'G Test String'),
					(object) array('integer' => 6, 'float' => 1.29999, 'string' => 'L Test String'),
					(object) array('integer' => 5, 'float' => 1.29999, 'string' => 'T Test String'),
					(object) array('integer' => 1, 'float' => 1.29999, 'string' => 'N Test String'),
				),
				'Should be sorted by the integer field in descending order',
				false
			),
			'by string ascending' => array(
				$input1,
				'string',
				1,
				false,
				false,
				array(
					(object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					(object) array('integer' => 35, 'float' => 1.29999, 'string' => 'C Test String'),
					(object) array('integer' => 15, 'float' => 1.29999, 'string' => 'C Test String'),
					(object) array('integer' => 22, 'float' => 1.29999, 'string' => 'E Test String'),
					(object) array('integer' => 6, 'float' => 1.29999, 'string' => 'G Test String'),
					(object) array('integer' => 6, 'float' => 1.29999, 'string' => 'L Test String'),
					(object) array('integer' => 1, 'float' => 1.29999, 'string' => 'N Test String'),
					(object) array('integer' => 5, 'float' => 1.29999, 'string' => 'T Test String'),
				),
				'Should be sorted by the string field in ascending order full argument list',
				false
			),
			'by string descending' => array(
				$input1,
				'string',
				-1,
				false,
				false,
				array(
					(object) array('integer' => 5, 'float' => 1.29999, 'string' => 'T Test String'),
					(object) array('integer' => 1, 'float' => 1.29999, 'string' => 'N Test String'),
					(object) array('integer' => 6, 'float' => 1.29999, 'string' => 'L Test String'),
					(object) array('integer' => 6, 'float' => 1.29999, 'string' => 'G Test String'),
					(object) array('integer' => 22, 'float' => 1.29999, 'string' => 'E Test String'),
					(object) array('integer' => 15, 'float' => 1.29999, 'string' => 'C Test String'),
					(object) array('integer' => 35, 'float' => 1.29999, 'string' => 'C Test String'),
					(object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				),
				'Should be sorted by the string field in descending order',
				false
			),
			'by casesensitive string ascending' => array(
				$input2,
				'string',
				1,
				true,
				false,
				array(
					(object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					(object) array('integer' => 35, 'float' => 1.29999, 'string' => 'C Test String'),
					(object) array('integer' => 15, 'float' => 1.29999, 'string' => 'C Test String'),
					(object) array('integer' => 22, 'float' => 1.29999, 'string' => 'E Test String'),
					(object) array('integer' => 6, 'float' => 1.29999, 'string' => 'L Test String'),
					(object) array('integer' => 1, 'float' => 1.29999, 'string' => 'N Test String'),
					(object) array('integer' => 6, 'float' => 1.29999, 'string' => 'g Test String'),
					(object) array('integer' => 5, 'float' => 1.29999, 'string' => 't Test String'),
				),
				'Should be sorted by the string field in ascending order with casesensitive comparisons',
				false
			),
			'by casesensitive string descending' => array(
				$input2,
				'string',
				-1,
				true,
				false,
				array(
					(object) array('integer' => 5, 'float' => 1.29999, 'string' => 't Test String'),
					(object) array('integer' => 6, 'float' => 1.29999, 'string' => 'g Test String'),
					(object) array('integer' => 1, 'float' => 1.29999, 'string' => 'N Test String'),
					(object) array('integer' => 6, 'float' => 1.29999, 'string' => 'L Test String'),
					(object) array('integer' => 22, 'float' => 1.29999, 'string' => 'E Test String'),
					(object) array('integer' => 35, 'float' => 1.29999, 'string' => 'C Test String'),
					(object) array('integer' => 15, 'float' => 1.29999, 'string' => 'C Test String'),
					(object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				),
				'Should be sorted by the string field in descending order with casesensitive comparisons',
				false
			),
			'by casesensitive string,integer ascending' => array(
				$input2,
				array('string','integer'),
				1,
				true,
				false,
				array(
					(object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					(object) array('integer' => 15, 'float' => 1.29999, 'string' => 'C Test String'),
					(object) array('integer' => 35, 'float' => 1.29999, 'string' => 'C Test String'),
					(object) array('integer' => 22, 'float' => 1.29999, 'string' => 'E Test String'),
					(object) array('integer' => 6, 'float' => 1.29999, 'string' => 'L Test String'),
					(object) array('integer' => 1, 'float' => 1.29999, 'string' => 'N Test String'),
					(object) array('integer' => 6, 'float' => 1.29999, 'string' => 'g Test String'),
					(object) array('integer' => 5, 'float' => 1.29999, 'string' => 't Test String'),
				),
				'Should be sorted by the string,integer field in descending order with casesensitive comparisons',
				false
			),
			'by casesensitive string,integer descending' => array(
				$input2,
				array('string','integer'),
				-1,
				true,
				false,
				array(
					(object) array('integer' => 5, 'float' => 1.29999, 'string' => 't Test String'),
					(object) array('integer' => 6, 'float' => 1.29999, 'string' => 'g Test String'),
					(object) array('integer' => 1, 'float' => 1.29999, 'string' => 'N Test String'),
					(object) array('integer' => 6, 'float' => 1.29999, 'string' => 'L Test String'),
					(object) array('integer' => 22, 'float' => 1.29999, 'string' => 'E Test String'),
					(object) array('integer' => 35, 'float' => 1.29999, 'string' => 'C Test String'),
					(object) array('integer' => 15, 'float' => 1.29999, 'string' => 'C Test String'),
					(object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				),
				'Should be sorted by the string,integer field in descending order with casesensitive comparisons',
				false
			),
			'by casesensitive string,integer ascending,descending' => array(
				$input2,
				array('string','integer'),
				array(1,-1),
				true,
				false,
				array(
					(object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
					(object) array('integer' => 35, 'float' => 1.29999, 'string' => 'C Test String'),
					(object) array('integer' => 15, 'float' => 1.29999, 'string' => 'C Test String'),
					(object) array('integer' => 22, 'float' => 1.29999, 'string' => 'E Test String'),
					(object) array('integer' => 6, 'float' => 1.29999, 'string' => 'L Test String'),
					(object) array('integer' => 1, 'float' => 1.29999, 'string' => 'N Test String'),
					(object) array('integer' => 6, 'float' => 1.29999, 'string' => 'g Test String'),
					(object) array('integer' => 5, 'float' => 1.29999, 'string' => 't Test String'),
				),
				'Should be sorted by the string,integer field in ascending,descending order with casesensitive comparisons',
				false
			),
			'by casesensitive string,integer descending,ascending' => array(
				$input2,
				array('string','integer'),
				array(-1,1),
				true,
				false,
				array(
					(object) array('integer' => 5, 'float' => 1.29999, 'string' => 't Test String'),
					(object) array('integer' => 6, 'float' => 1.29999, 'string' => 'g Test String'),
					(object) array('integer' => 1, 'float' => 1.29999, 'string' => 'N Test String'),
					(object) array('integer' => 6, 'float' => 1.29999, 'string' => 'L Test String'),
					(object) array('integer' => 22, 'float' => 1.29999, 'string' => 'E Test String'),
					(object) array('integer' => 15, 'float' => 1.29999, 'string' => 'C Test String'),
					(object) array('integer' => 35, 'float' => 1.29999, 'string' => 'C Test String'),
					(object) array('integer' => 12, 'float' => 1.29999, 'string' => 'A Test String'),
				),
				'Should be sorted by the string,integer field in descending,ascending order with casesensitive comparisons',
				false
			),
			'by casesensitive string ascending' => array(
				$input3,
				'string',
				1,
				true,
				array('fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'),
				array(
					(object) array('string' => 'A Test String', 'integer' => 1, ),
					(object) array('string' => 'é Test String', 'integer' => 2, ),
					(object) array('string' => 'É Test String', 'integer' => 4, ),
					(object) array('string' => 'è Test String', 'integer' => 3, ),
					(object) array('string' => 'È Test String', 'integer' => 5, ),
					(object) array('string' => 'L Test String', 'integer' => 8, ),
					(object) array('string' => 'œ Test String', 'integer' => 7, ),
					(object) array('string' => 'Œ Test String', 'integer' => 6, ),
					(object) array('string' => 'p Test String', 'integer' => 10, ),
					(object) array('string' => 'P Test String', 'integer' => 9, ),
				),
				'Should be sorted by the string field in ascending order with casesensitive comparisons and fr_FR locale',
				false
			),
			'by caseinsensitive string, integer ascending' => array(
				$input3,
				array('string', 'integer'),
				1,
				false,
				array('fr_FR.utf8', 'fr_FR.UTF-8', 'fr_FR.UTF-8@euro', 'French_Standard', 'french', 'fr_FR', 'fre_FR'),
				array(
					(object) array('string' => 'A Test String', 'integer' => 1, ),
					(object) array('string' => 'é Test String', 'integer' => 2, ),
					(object) array('string' => 'É Test String', 'integer' => 4, ),
					(object) array('string' => 'è Test String', 'integer' => 3, ),
					(object) array('string' => 'È Test String', 'integer' => 5, ),
					(object) array('string' => 'L Test String', 'integer' => 8, ),
					(object) array('string' => 'Œ Test String', 'integer' => 6, ),
					(object) array('string' => 'œ Test String', 'integer' => 7, ),
					(object) array('string' => 'P Test String', 'integer' => 9, ),
					(object) array('string' => 'p Test String', 'integer' => 10, ),
				),
				'Should be sorted by the string,integer field in ascending order with caseinsensitive comparisons and fr_FR locale',
				false
			),
		);
	}

	/**
	 * test sorting an array of objects.
	 *
	 * @param	array	$input		Input array of objects
	 * @param	mixed	$key		Key to sort on
	 * @param	mixed	$direction	Ascending (1) or Descending(-1)
	 * @param	array	$expect		The expected results
	 * @param	string	$message	The failure message
	 * @param	bool	$defaults	Use the defaults (true) or full argument list
	 *
	 * @return void
	 * @dataProvider getTestSortObjectData
	 */
	public function testSortObjects(
		$input, $key, $direction, $casesensitive, $locale, $expect, $message, $defaults)
	{
		// Skip for MAC until PHP sort bug is fixed
		if (substr(php_uname(), 0, 6) != 'Darwin')
		{
			if ($defaults) {
				$output = JArrayHelper::sortObjects($input, $key);
			}
			else {
				$output = JArrayHelper::sortObjects($input, $key, $direction, $casesensitive, $locale);
			}

			$this->assertEquals(
			$expect,
			$output,
			$message
			);
		}
	}
}
