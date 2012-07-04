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
class FieldValidator extends Object {

    /**
     * Determines whether a field is valid or not based on its value
     *
     * @param FormField $field Field to validate
     * @param Validator $validator Validator class to use for error reporting
     * @return bool
     */
    function validate($field, $validator) {
        return true;
    }
}
