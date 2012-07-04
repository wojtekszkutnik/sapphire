<?php
/**
 * Dropdown field, created from a <select> tag.
 * 
 * <b>Setting a $has_one relation</b>
 * 
 * Using here an example of an art gallery, with Exhibition pages, 
 * each of which has a Gallery they belong to.  The Gallery class is also user-defined.
 * <code>
 * 	static $has_one = array(
 * 		'Gallery' => 'Gallery',
 * 	);
 * 
 * 	public function getCMSFields() {
 * 		$fields = parent::getCMSFields();
 * 		$field = new DropdownField('GalleryID', 'Gallery', Gallery::get()->map('ID', 'Title'));
 * 		$field->setEmptyString('(Select one)');
 * 		$fields->addFieldToTab('Root.Content', $field, 'Content');
 * </code>
 * 
 * As you see, you need to put "GalleryID", rather than "Gallery" here.
 * 
 * <b>Populate with Array</b>
 * 
 * Example model defintion:
 * <code>
 * class MyObject extends DataObject {
 *   static $db = array(
 *     'Country' => "Varchar(100)"
 *   );
 * }
 * </code>
 * 
 * Example instantiation:
 * <code>
 * new DropdownField(
 *   'Country',
 *   'Country',
 *   array(
 *     'NZ' => 'New Zealand',
 *     'US' => 'United States'
 *     'GEM'=> 'Germany'
 *   )
 * );
 * </code>
 * 
 * <b>Populate with Enum-Values</b>
 * 
 * You can automatically create a map of possible values from an {@link Enum} database column.
 * 
 * Example model definition:
 * <code>
 * class MyObject extends DataObject {
 *   static $db = array(
 *     'Country' => "Enum('New Zealand,United States,Germany','New Zealand')"
 *   );
 * }
 * </code>
 * 
 * Field construction:
 * <code>
 * new DropdownField(
 *   'Country',
 *   'Country',
 *   singleton('MyObject')->dbObject('Country')->enumValues()
 * );
 * </code>
 * 
 * @see CheckboxSetField for multiple selections through checkboxes instead.
 * @see ListboxField for a single <select> box (with single or multiple selections).
 * @see TreeDropdownField for a rich and customizeable UI that can visualize a tree of selectable elements
 * 
 * @package forms
 * @subpackage fields-basic
 */
class DropdownField extends FormField {

	/**
	 * @var boolean $source Associative or numeric array of all dropdown items,
	 * with array key as the submitted field value, and the array value as a
	 * natural language description shown in the interface element.
	 */
	protected $source;

	/**
	 * @var boolean $isSelected Determines if the field was selected
	 * at the time it was rendered, so if {@link $value} matches on of the array
	 * values specified in {@link $source}
	 */
	protected $isSelected;

	/**
	 * @var boolean $hasEmptyDefault Show the first <option> element as
	 * empty (not having a value), with an optional label defined through
	 * {@link $emptyString}. By default, the <select> element will be
	 * rendered with the first option from {@link $source} selected.
	 */
	protected $hasEmptyDefault = false;

	/**
	 * @var string $emptyString The title shown for an empty default selection,
	 * e.g. "Select...".
	 */
	protected $emptyString = '';

	/**
	 * Creates a new dropdown field.
	 * @param string $name The field name
	 * @param mixed $title The field title
	 * @param array $source An map of the dropdown items
	 * @param string $value The current value
	 * @param Form $form (Deprecated) The parent form
	 * @param mixed $emptyString Add an empty selection on to of the {@link $source}-Array
	 * 	(can also be boolean, which results in an empty string)
	 *  Argument is deprecated in 3.1, please use {@link setEmptyString()} and/or {@link setHasEmptyDefault(true)} instead.
	 */
	function __construct($name, $title = null, $source = array(), $value = '', $form = null, $emptyString = null) {
		if ($form) {
			Deprecation::notice('3.1', 'Form argument is now ignored. It was not used for some time and passing it has no effect.');
		}
		$this->setSource($source);

		if($emptyString === true) {
			Deprecation::notice('3.1', 'Please use setHasEmptyDefault(true) instead of passing a boolean true $emptyString argument');
		}
		if(is_string($emptyString)) {
			Deprecation::notice('3.1', 'Please use setEmptyString() instead of passing a string $emptyString argument.');
		}

		if($emptyString) $this->setHasEmptyDefault(true);
		if(is_string($emptyString)) $this->setEmptyString($emptyString);

		parent::__construct($name, ($title===null) ? $name : $title, $value);
	}

	function Field($properties = array()) {
		$source = $this->getSource();
		$options = array();
		if($source) {
			// SQLMap needs this to add an empty value to the options
			if(is_object($source) && $this->emptyString) {
				$options[] = new ArrayData(array(
					'Value' => '',
					'Title' => $this->emptyString,
				));
			}

			foreach($source as $value => $title) {
				$selected = false;
				if($value === '' && ($this->value === '' || $this->value === null)) {
					$selected = true;
				} else {
					// check against value, fallback to a type check comparison when !value
					$selected = ($value) ? $value == $this->value : $value === $this->value;
					$this->isSelected = $selected;
				}

				$options[] = new ArrayData(array(
					'Title' => $title,
					'Value' => $value,
					'Selected' => $selected,
				));
			}
		}

		$properties = array_merge($properties, array('Options' => new ArrayList($options)));

		return parent::Field($properties);
	}

	function getAttributes() {
		return array_merge(
			parent::getAttributes(),
			array('type' => null, 'value' => null)
		);
	}

	/**
	 * @return boolean
	 */
	function isSelected() {
		return $this->isSelected;
	}

	/**
	 * Gets the source array including any empty default values.
	 * 
	 * @return array
	 */
	function getSource() {
		if(is_array($this->source) && $this->getHasEmptyDefault()) {
			return array('' => $this->emptyString) + (array) $this->source;
		} else {
			return $this->source;
		}
	}

	/**
	 * @param array $source
	 */
	function setSource($source) {
		$this->source = $source;
		return $this;
	}

	/**
	 * @param boolean $bool
	 */
	function setHasEmptyDefault($bool) {
		$this->hasEmptyDefault = $bool;
		return $this;
	}

	/**
	 * @return boolean
	 */
	function getHasEmptyDefault() {
		return $this->hasEmptyDefault;
	}

	/**
	 * Set the default selection label, e.g. "select...".
	 * Defaults to an empty string. Automatically sets
	 * {@link $hasEmptyDefault} to true.
	 *
	 * @param string $str
	 */
	function setEmptyString($str) {
		$this->setHasEmptyDefault(true);
		$this->emptyString = $str;
		return $this;
	}

	/**
	 * @return string
	 */
	function getEmptyString() {
		return $this->emptyString;
	}

	function performReadonlyTransformation() {
		$field = new LookupField($this->name, $this->title, $this->source);
		$field->setValue($this->value);
		$field->setForm($this->form);
		$field->setReadonly(true);
		return $field;
	}
}
