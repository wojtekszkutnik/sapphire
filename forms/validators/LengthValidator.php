<?php
/*
 * Validates FormField's length
 */
class LengthValidator extends FieldValidator {

    protected $params = array();

    /**
     * @param int $length
     * @param string $comparison 'lte' for 'lower than or equal',
     *                    'gte' for 'greater than or equal'
     */
    function __construct($comparison, $length) {
        $this->setParam('length', $length)
             ->setParam('comparison', $comparison);
    }

    function validate($field, $validator) {
        $comparison = $this->getParam('comparison');
        $length = $this->getParam('length');
        $field_value = $field->Value();
        $is_valid = false;
        if ($comparison=='gte') {
            if (strlen($field_value) >= $length) {
                $is_valid = true;
            }
            else {
                $validator->validationError(
                    $field->getName(),
                    _t('FormField.VALIDATION', sprintf("This field should be at least %d characters long", $length)),
                    "validation"
                );
            }
        }
        else if ($comparison=='lte') {
            if (strlen($field_value) <= $length) {
                $is_valid = true;
            }
            else {
                $validator->validationError(
                    $field->getName(),
                    _t('FormField.VALIDATION', sprintf("This field should be at most %d characters long", $length)),
                    "validation"
                );
            }
        }
        return $is_valid;
    }
}
