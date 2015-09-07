<?php
/**
 * Created by PhpStorm.
 * User: jopes
 * Date: 2015-09-07
 * Time: 19:58
 */

namespace model;


class ErrorArrayModel {

    private $errors = [];

    // Constructor
    public function __construct() {
    }

    // Methods
    public function addError(\Exception $exception) {
        $this->errors[] = $exception;
    }

    public function getLastErrorMessage() {
        $error = end($this->errors);

        return $error->getMessage();
    }

    public function getLastError() {
        return end($this->errors);
    }

    public function getAllErrors() {
        return $this->errors;
    }

    public function hasErrors() {
        return sizeof($this->errors) > 0;
    }
} 