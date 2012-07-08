<?php
/**
 * @package framework
 * @subpackage tests
 */
class EmailValidatorTest extends SapphireTest {
    function setUp() {
        parent::setUp();
        $this->field = new EmailField('Email', null, 'john.doe@example.com');
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
        $this->field->setValue('invalidemail@@gmail.com');
        $validator->expects($this->once())
            ->method('validationError')
            ->with('Email',
            _t('FormField.VALIDATION', "Please enter an email address"),
            "validation");
        $validator->setForm($this->form);
        $this->email_validator->validate($this->field, $validator);
    }

    /**
     * Check the php validator for email addresses. We should be checking against RFC 5322 which defines email address
     * syntax.
     *
     * @TODO
     *   - double quotes around the local part (before @) is not supported
     *   - special chars ! # $ % & ' * + - / = ? ^ _ ` { | } ~ are all valid in local part
     *   - special chars ()[]\;:,<> are valid in the local part if the local part is in double quotes
     *   - "." is valid in the local part as long as its not first or last char
     * @return void
     */
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
