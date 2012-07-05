<?php
/**
 * Validates for RFC 2822 compliant email adresses.
 *
 * @see http://www.regular-expressions.info/email.html
 git* @see http://www.ietf.org/rfc/rfc2822.txt
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
                _t('FormField.VALIDATION', "Please enter an email address"),
                "validation"
            );
            return false;
        } else{
            return true;
        }
    }
}
