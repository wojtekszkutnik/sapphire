<?php
/**
 * @package framework
 * @subpackage tests
 */

class TestFieldValidator extends FieldValidator {
    function validate($field, $validator) {
        return true;
    }
}

class FieldValidatorTest extends SapphireTest {
    function setUp() {
        parent::setUp();
        $this->testValidator = new TestFieldValidator();
    }

    function testGettersSetters() {
        $this->testValidator->setParam('test_param', 'test value');
        $this->assertEquals('test value',
                            $this->testValidator->getParam('test_param'));
    }

    function testGetterReturnsDefaultWhenNoValueIsPresent() {
        $dummy_key = 'no_such_value';
        $dummy_text = 'default yummy dummy';
        $dummy_text_2 = 'new dummy';

        // For non-existant key should return default value
        $this->assertEquals($dummy_text,
            $this->testValidator->getParam($dummy_key, $dummy_text));

        // After setting the param, should return the param value even if default value is provided
        $this->testValidator->setParam($dummy_key, $dummy_text_2);
        $this->assertEquals($dummy_text_2,
                            $this->testValidator->getParam($dummy_key, $dummy_text));

    }
}