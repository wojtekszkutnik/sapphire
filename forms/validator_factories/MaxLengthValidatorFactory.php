<?php
class MaxLengthValidatorFactory extends FieldValidatorFactory {
	function getValidator($length) {
		return new LengthValidator($length, 'lte');
	}
}
