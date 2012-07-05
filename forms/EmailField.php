<?php
/**
 * Text input field with validation for correct email format
 * according to RFC 2822.
 * 
 * @package forms
 * @subpackage fields-formattedinput
 */
class EmailField extends TextField {

	protected $validators = array('EmailValidator');

	function Type() {
		return 'email text';
	}

	function getAttributes() {
		return array_merge(
			parent::getAttributes(),
			array(
				'type' => 'email'
			)
		);
	}
}
