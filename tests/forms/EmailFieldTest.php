<?php

class EmailFieldTest extends SapphireTest {

	function testEmailAddressSyntax() {
		$this->internalCheck("blah@blah.com", "Valid, simple", true);
		$this->internalCheck("mr.o'connor+on-toast@blah.com", "Valid, special chars", true);
		$this->internalCheck("", "Empty email", true);
		$this->internalCheck("invalid", "Invalid, simple", false);
		$this->internalCheck("invalid@name@domain.com", "Invalid, two @'s", false);
		$this->internalCheck("invalid@domain", "Invalid, domain too simple", false);
		$this->internalCheck("domain.but.no.user", "Invalid, no user part", false);
	}

	function internalCheck($email, $checkText, $expectSuccess) {
		$field = new EmailField("MyEmail");
		$field->setValue($email);
        $form = new Form(new Controller(),
            'Form',
            new FieldList(
                array($field)
            ),
            new FieldList());

		$val = new EmailFieldTest_Validator();
        $val->setForm($form);
		try {
			$val->validate($field);
			if (!$expectSuccess) $this->assertTrue(false, $checkText . " (/$email/ passed validation, but not expected to)");
		} catch (Exception $e) {
			if ($e instanceof PHPUnit_Framework_AssertionFailedError) throw $e; // re-throw assertion failure
			else if ($expectSuccess) $this->assertTrue(false, $checkText . ": " . $e->GetMessage() . " (/$email/ did not pass validation, but was expected to)");
		}
	}
}

class EmailFieldTest_Validator extends Validator {
	function validationError($fieldName, $message, $messageType='') {
		throw new Exception($message);
	}

	function javascript() {
	}

	function php($data) {
	}
}
