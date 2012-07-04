<?php
/**
 * Validates e-mails
 */
class EmailValidator extends FieldValidator{
    function validate($field, $validator) {
        $field_value = trim($field->Value());

        $pcrePattern = '^[a-z0-9!#$%&\'*+/=?^_`{|}~-]+(?:\\.[a-z0-9!#$%&\'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$';

        // PHP uses forward slash (/) to delimit start/end of pattern, so it must be escaped
        $pregSafePattern = str_replace('/', '\\/', $pcrePattern);

        if($field_value && !preg_match('/' . $pregSafePattern . '/i', $field_value)){
            $validator->validationError(
                $field->getName(),
                _t('EmailField.VALIDATION', "Please enter an email address"),
                "validation"
            );
            return false;
        } else{
            return true;
        }
    }
}
