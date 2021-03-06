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
				$result = is_null($value) || (!is_null($param) && strlen($value) <= $param);
				break;
			case 'min':
				$result = is_null($value) || (!is_null($param) && strlen($value) >= $param);
				break;
			case 'required':
				$result =  !is_null($value);
				break;
			case 'numeric':
				$result = (bool) preg_match("/^[0-9]{1,}$/", $value);
				break;
			case 'email':
				$result = !is_null($value) ? !!filter_var(filter_var($value, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL) : true;
				break;
			case 'unique':
				$result = $this->validateUniqueField($param, $value);
				break;
			default:
				throw new \Exception("Validation type not implemented", 400);
		}
		if ($result !== true) {
			$message = $this->message($field, $type, $param);
			$this->details[$field][] = $message;
		}
		return $result;
	}

	protected function validateUniqueField($params, $value)
	{
		$options = explode(",", $params);
		$query = new QueryBuilder($options[0]);

		if (count($options) > 2) {
			$conditionals = "$options[1] = '$value' AND $options[2] != $options[3]";
		} else {
			$conditionals = "$options[1] = '$value'";
		}

		$elements = $query->where($conditionals)->get();
		if (count($elements) > 0) {
			return  false;
		}
		return true;
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
			case 'unique':
				return "The field {$field} is already being used.";
		}
	}
}
