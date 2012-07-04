<?php
/**
 * Text input field.
 *
 * @package forms
 * @subpackage fields-basic
 */
class TextField extends FormField {

	/**
	 * @var int
	 */
	protected $maxLength;
	
	/**
	 * Returns an input field, class="text" and type="text" with an optional maxlength
	 */
	function __construct($name, $title = null, $value = null, $maxLength = null, $form = null) {
        if ($form) {
            Deprecation::notice('3.1', 'Form argument is now ignored. It was not used for some time and passing it has no effect.');
        }
		$this->maxLength = $maxLength;
		
		parent::__construct($name, $title, $value);
	}
	
	/**
	 * @param int $length
	 */
	function setMaxLength($length) {
		$this->maxLength = $length;
		
		return $this;
	}
	
	/**
	 * @return int
	 */
	function getMaxLength() {
		return $this->maxLength;
	}

	function getAttributes() {
		return array_merge(
			parent::getAttributes(),
			array(
				'maxlength' => $this->getMaxLength(),
				'size' => ($this->getMaxLength()) ? min($this->getMaxLength(), 30) : null
			)
		);
	}

	function InternallyLabelledField() {
		if(!$this->value) $this->value = $this->Title();
		return $this->Field();
	}
	
}
