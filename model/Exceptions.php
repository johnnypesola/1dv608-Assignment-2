<?php
/**
 * Created by PhpStorm.
 * User: jopes
 * Date: 2015-09-07
 * Time: 19:58
 */

namespace model;


class Exceptions {

    private $exceptions = [];

    // Constructor
    public function __construct() {
    }

    // Methods
    public function AddException(\Exception $exception) {
        $this->exceptions[] = $exception;
    }

    public function GetLastExceptionMessage() {
        $error = end($this->exceptions);

        return $error->getMessage();
    }

    public function GetLastException() {
        return end($this->exceptions);
    }

    public function GetAllExceptions() {
        return $this->exceptions;
    }

    public function HasExceptions() {
        return sizeof($this->exceptions) > 0;
    }
} 