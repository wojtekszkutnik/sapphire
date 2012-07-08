<?php
/**
 * @package framework
 * @subpackage tests
 */
class EmailValidatorTest extends SapphireTest {
    function testEmailValidatorCallsValidationErrorOnInvalidEmailAddress() {
        $validator = $this->getMock('Validator', array('validationError'));
        $field = new EmailField('Email', null, 'john.doeexample.com');
        $form = new Form(new Controller(), 'Form', new FieldList(array($field)), new FieldList());
        $email_validator = new EmailValidator();
        $validator->expects($this->once())
            ->method('validationError')
            ->with('Email',
            _t('FormField.VALIDATION', "Please enter an email address"),
            "validation");
        $validator->setForm($form);
        $email_validator->validate($field, $validator);
    }

}
