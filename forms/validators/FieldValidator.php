<?php
/**
 * This validation class handles per-field validation rules.
 * One field can be handled by multiple FieldValidators.
 *
 * Acts as a visitor to individual form fields.
 *
 * @package forms
 * @subpackage validators
 */
abstract class FieldValidator extends Object {

    protected $params = array();

    /**
     * Determines whether a field is valid or not based on its value
     *
     * @param FormField $field Field to validate
     * @param Validator $validator Validator class to use for error reporting
     * @return bool
     */
    abstract function validate($field, $validator);

    function setParam($name, $value) {
        $this->params[$name] = $value;
    }

    function getParam($name, $default=null) {
        if (array_key_exists($name, $this->params))
            return $this->params[$name];
        else
            return $default;
    }
}
