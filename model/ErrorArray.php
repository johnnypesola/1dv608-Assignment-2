<?php
/**
 * Created by PhpStorm.
 * User: jopes
 * Date: 2015-09-07
 * Time: 19:58
 */

namespace model;


class ErrorArray {

    private $errors = [];

    // Constructor
    public function __construct() {
    }

    // Methods
    public function AddError(\Exception $exception) {
        $this->errors[] = $exception;
    }

    public function GetLastErrorMessage() {
        $error = end($this->errors);

        return $error->getMessage();
    }

    public function GetLastError() {
        return end($this->errors);
    }

    public function GetAllErrors() {
        return $this->errors;
    }

    public function HasErrors() {
        return sizeof($this->errors) > 0;
    }
} 