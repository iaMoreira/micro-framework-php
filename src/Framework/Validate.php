<?php

namespace Framework;

class Validate
{

	protected $errors;
	protected $details;

	public function set($data)
	{

		if (!is_array($data)) {
			$data = (array) $data;
		}
		$values = request()->all();

		foreach ($data as $field => $validation) {
			$value = isset($values[$field]) ? $values[$field] : null; 
			$this->value($value, $validation, $field);
		}
		return $this->details;
	}


	public function value($value, $validation, $field)
	{

		$pieces = explode('|', $validation);
		foreach ($pieces as $validate) {
			$options = explode(":", $validate);
			if (count($options) > 1) {
				$this->errors[] = $this->validation($field, $options[0], $value, $options[1]);
			} else {
				$this->errors[] = $this->validation($field, $options[0], $value);
			}
		}
		return !array_search(false, $this->errors, true);
	}


	protected function validation($field, $type, $value, $param = null)
	{

		switch ($type) {
			case 'max':
				$result = !is_null($param) && strlen($value) <= $param;
				break;
			case 'min':
				$result =  !is_null($param) && strlen($value) >= $param;
				break;
			case 'required':
				$result =  !is_null($value);
				break;
			case 'numeric':
				$result =  preg_match("/^[0-9]{1,}$/", $value);
				break;
			case 'email':
				$result = !is_null($value) ? !!filter_var(filter_var($value, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL) : true;
				break;
			default:
				throw new \Exception("Tipo de validação não implementado");
		}
		if ($result !== true) {
			$message = $this->message($field, $type, $param);
			$this->details[$field][] = $message;
		}
		return $result;
	}

	protected function message($field, $type, $param = null)
	{
		switch ($type) {
			case 'max':
				return "The field {$field} supports the maximum length of {$param} characters.";
			case 'min':
				return "The field {$field} requires a minimum of {$param} characters.";
			case 'required':
				return "The field {$field} is required.";
			case 'numeric':
				return "The field {$field} supports only numeric values.";
			case 'email':
				return "The field {$field} is invalid.";
		}
	}
}
