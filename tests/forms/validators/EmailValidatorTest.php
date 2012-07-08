<?php
/**
 * @package framework
 * @subpackage tests
 */
class EmailValidatorTest extends SapphireTest {
    function setUp() {
        parent::setUp();
        $this->field = new EmailField('Email', null, 'john.doeexample.com');
        $this->form = new Form(new Controller(),
                               'Form',
                               new FieldList(array($this->field)),
                               new FieldList());
        $this->email_validator = new EmailValidator();
        $this->validator = new Validator();
        $this->validator->setForm($this->form);
    }

    function testEmailValidatorCallsValidationErrorOnInvalidEmailAddress() {
        $validator = $this->getMock('Validator', array('validationError'));
        $field = new EmailField('Email', null, 'john.doeexample.com');
        $form = new Form(new Controller(), 'Form', new FieldList(array($field)), new FieldList());
        $validator->expects($this->once())
            ->method('validationError')
            ->with('Email',
            _t('FormField.VALIDATION', "Please enter an email address"),
            "validation");
        $validator->setForm($form);
        $this->email_validator->validate($field, $validator);
    }

    function testValidatorProperlyValidatesEmailValues() {
		$this->internalValueTest("blah@blah.com", true);
		$this->internalValueTest("mr.o'connor+on-toast@blah.com", true);
		$this->internalValueTest("", true);
		$this->internalValueTest("invalid", false);
		$this->internalValueTest("invalid@name@domain.com", false);
		$this->internalValueTest("invalid@domain", false);
		$this->internalValueTest("domain.but.no.user", false);
    }

    function internalValueTest($value, $expected) {
        $this->field->setValue($value);
        $this->assertEquals($expected,
                            $this->email_validator->validate($this->field, $this->validator));
    }
}
