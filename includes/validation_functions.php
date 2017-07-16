<?php 

	$errors = array();

	//Convert fields to proper names 
	function fieldname_as_text($fieldname) {
		$fieldname = str_replace("_", " ", $fieldname);
		$fieldname = ucfirst($fieldname);
		return $fieldname;
	}

	// Presence
	function has_value($value){
		return isset($value) && $value !== "";
	}

	//Check for presence of required fields
	function validate_presences($required_fields) {
		global $errors;

		foreach ($required_fields as $field) {
			$value = trim($_POST[$field]);
			if(!has_value($value)) {
				$errors[$field] = fieldname_as_text($field. " can't be blank");
			}
		}
	}

	// String length 
	function has_min_length($value, $min){
		return strlen($value) >= $min;
	}

	//String length
	function has_max_length($value, $max){
		return strlen($value) > $max;
	}

	//Check for valid lengths
	function validate_max_lengths($fields_with_max_lengths) {
		global $errors;

		foreach ($fields_with_max_lengths as $field => $max) {
			$value = trim($_POST[$field]);
			if (has_max_length($value, $max)){
				$errors[$field] = fieldname_as_text($field. " is too long");
			}
		}
	}

	// Inclusion in a set
	function in_set($value, $set){
		return in_array($value, $set);
	}

?>