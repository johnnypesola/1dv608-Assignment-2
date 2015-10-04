<?php

namespace model;


class ExceptionsService {

// Init variables
    private $exceptions = [];

// Constructor

// Getters and Setters

// Private Methods

// Public Methods
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