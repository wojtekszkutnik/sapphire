<?php
/**
 * This validation class handles all field-specific validation
 *
 * @package forms
 * @subpackage validators
 */
abstract class FieldValidator extends Object {
    /**
     * @return array Errors (if any)
     */
    abstract function validate($field);
}
